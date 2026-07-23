<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ExpenseClaim;
use App\Models\ExpenseType;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseService
{
    /**
     * Submit a new expense claim
     */
    public function submitClaim(Employee $employee, array $data): ExpenseClaim
    {
        $expenseType = ExpenseType::findOrFail($data['expense_type_id']);

        $claim = ExpenseClaim::create([
            'employee_id' => $employee->id,
            'expense_type_id' => $expenseType->id,
            'claim_number' => ExpenseClaim::generateClaimNumber(),
            'expense_date' => $data['expense_date'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'SAR',
            'payment_type' => $expenseType->isReimbursable() ? 'reimbursable' : 'deductible',
            'status' => $expenseType->requires_approval ? 'submitted' : 'approved',
        ]);

        // Handle attachments if any
        if (!empty($data['attachments'])) {
            $this->attachFiles($claim, $data['attachments']);
        }

        Log::info('Expense claim submitted', [
            'claim_id' => $claim->id,
            'claim_number' => $claim->claim_number,
            'employee_id' => $employee->id,
            'amount' => $claim->amount,
        ]);

        return $claim;
    }

    /**
     * Approve an expense claim
     */
    public function approveClaim(ExpenseClaim $claim, User $reviewer): bool
    {
        return $claim->approve($reviewer);
    }

    /**
     * Reject an expense claim
     */
    public function rejectClaim(ExpenseClaim $claim, User $reviewer, string $reason): bool
    {
        return $claim->reject($reviewer, $reason);
    }

    /**
     * Apply approved claims to payroll
     */
    public function applyToPayroll(Payroll $payroll): array
    {
        $results = [
            'reimbursable' => [],
            'deductible' => [],
            'errors' => [],
        ];

        DB::beginTransaction();
        try {
            // Get all approved claims for this employee
            $claims = ExpenseClaim::where('employee_id', $payroll->employee_id)
                ->where('status', 'approved')
                ->get();

            foreach ($claims as $claim) {
                // Determine if it should be added or subtracted from payroll
                if ($claim->isReimbursable()) {
                    $results['reimbursable'][] = [
                        'claim' => $claim,
                        'amount' => $claim->amount,
                    ];
                } else {
                    $results['deductible'][] = [
                        'claim' => $claim,
                        'amount' => $claim->amount,
                    ];
                }

                // Mark claim as applied
                $claim->applyToPayroll($payroll);
            }

            // Update payroll deductions with expense amounts
            $this->updatePayrollExpenses($payroll, $results);

            DB::commit();

            Log::info('Expenses applied to payroll', [
                'payroll_id' => $payroll->id,
                'employee_id' => $payroll->employee_id,
                'reimbursable_count' => count($results['reimbursable']),
                'deductible_count' => count($results['deductible']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $results['errors'][] = $e->getMessage();
            Log::error('Failed to apply expenses to payroll', [
                'payroll_id' => $payroll->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        return $results;
    }

    /**
     * Update payroll with expense adjustments
     */
    protected function updatePayrollExpenses(Payroll $payroll, array $results): void
    {
        $existingDeductions = $payroll->deductions ?? [];
        
        // Add deductible expenses as deductions
        foreach ($results['deductible'] as $item) {
            $existingDeductions['expense_' . $item['claim']->id] = [
                'type' => 'expense_deduction',
                'description' => $item['claim']->description,
                'amount' => $item['amount'],
                'claim_number' => $item['claim']->claim_number,
            ];
        }

        $payroll->update([
            'deductions' => $existingDeductions,
        ]);
    }

    /**
     * Get total reimbursable amount for employee in a period
     */
    public function getReimbursableTotal(Employee $employee, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = ExpenseClaim::where('employee_id', $employee->id)
            ->reimbursable()
            ->whereIn('status', ['approved', 'applied_to_payroll']);

        if ($startDate) {
            $query->where('expense_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('expense_date', '<=', $endDate);
        }

        return (float) $query->sum('amount');
    }

    /**
     * Get total deductible amount for employee in a period
     */
    public function getDeductibleTotal(Employee $employee, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = ExpenseClaim::where('employee_id', $employee->id)
            ->deductible()
            ->whereIn('status', ['approved', 'applied_to_payroll']);

        if ($startDate) {
            $query->where('expense_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('expense_date', '<=', $endDate);
        }

        return (float) $query->sum('amount');
    }

    /**
     * Get pending claims for an employee
     */
    public function getPendingClaims(Employee $employee): \Illuminate\Database\Eloquent\Collection
    {
        return ExpenseClaim::where('employee_id', $employee->id)
            ->pending()
            ->with('expenseType')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get expense summary by type for a period
     */
    public function getExpenseSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $query = ExpenseClaim::query();

        if ($startDate) {
            $query->where('expense_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('expense_date', '<=', $endDate);
        }

        $claims = $query->with('expenseType')->get();

        $summary = [
            'total' => $claims->sum('amount'),
            'reimbursable_total' => $claims->where('payment_type', 'reimbursable')->sum('amount'),
            'deductible_total' => $claims->where('payment_type', 'deductible')->sum('amount'),
            'by_status' => [],
            'by_type' => [],
            'by_employee' => [],
        ];

        // Group by status
        foreach (ExpenseClaim::STATUSES as $status => $label) {
            $summary['by_status'][$status] = [
                'label' => $label,
                'count' => $claims->where('status', $status)->count(),
                'total' => $claims->where('status', $status)->sum('amount'),
            ];
        }

        // Group by expense type
        $byType = $claims->groupBy('expense_type_id');
        foreach ($byType as $typeId => $typeClaims) {
            $type = $typeClaims->first()->expenseType;
            if ($type) {
                $summary['by_type'][$type->name] = [
                    'count' => $typeClaims->count(),
                    'total' => $typeClaims->sum('amount'),
                ];
            }
        }

        // Group by employee
        $byEmployee = $claims->groupBy('employee_id');
        foreach ($byEmployee as $empId => $empClaims) {
            $employee = $empClaims->first()->employee;
            if ($employee) {
                $summary['by_employee'][$employee->name] = [
                    'count' => $empClaims->count(),
                    'total' => $empClaims->sum('amount'),
                ];
            }
        }

        return $summary;
    }

    /**
     * Attach files to an expense claim
     */
    public function attachFiles(ExpenseClaim $claim, array $files): void
    {
        foreach ($files as $fileData) {
            $claim->attachments()->create([
                'file_path' => $fileData['path'],
                'original_name' => $fileData['original_name'] ?? 'receipt',
                'mime_type' => $fileData['mime_type'] ?? 'application/octet-stream',
                'file_size' => $fileData['size'] ?? 0,
                'media_id' => $fileData['media_id'] ?? null,
            ]);
        }
    }

    /**
     * Bulk approve claims
     */
    public function bulkApprove(array $claimIds, User $reviewer): array
    {
        $results = ['success' => [], 'failed' => []];

        foreach ($claimIds as $claimId) {
            $claim = ExpenseClaim::find($claimId);
            if ($claim && $claim->canBeApproved()) {
                $claim->approve($reviewer);
                $results['success'][] = $claim->claim_number;
            } else {
                $results['failed'][] = $claimId;
            }
        }

        return $results;
    }

    /**
     * Get claims awaiting approval
     */
    public function getPendingApproval(?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = ExpenseClaim::pending()
            ->with(['employee.user', 'expenseType']);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }
}
