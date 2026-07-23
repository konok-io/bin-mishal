<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseClaim extends Model
{
    use HasFactory;

    protected $table = 'expense_claims';

    protected $fillable = [
        'employee_id',
        'expense_type_id',
        'claim_number',
        'expense_date',
        'description',
        'amount',
        'currency',
        'payment_type',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'applied_to_payroll_id',
        'applied_to_payroll_date',
        'admin_notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'reviewed_at' => 'datetime',
        'applied_to_payroll_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public const STATUSES = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'manager_review' => 'Manager Review',
        'hr_review' => 'HR Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'paid' => 'Paid',
        'applied_to_payroll' => 'Applied to Payroll',
    ];

    public const STATUS_COLORS = [
        'draft' => 'gray',
        'submitted' => 'info',
        'manager_review' => 'warning',
        'hr_review' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'paid' => 'success',
        'applied_to_payroll' => 'primary',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'applied_to_payroll_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ExpenseAttachment::class);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'manager_review', 'hr_review']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeAppliedToPayroll($query)
    {
        return $query->where('status', 'applied_to_payroll');
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeReimbursable($query)
    {
        return $query->where('payment_type', 'reimbursable');
    }

    public function scopeDeductible($query)
    {
        return $query->where('payment_type', 'deductible');
    }

    public function isReimbursable(): bool
    {
        return $this->payment_type === 'reimbursable';
    }

    public function isDeductible(): bool
    {
        return $this->payment_type === 'deductible';
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['submitted', 'manager_review', 'hr_review']);
    }

    public function canBeApproved(): bool
    {
        return $this->isPending();
    }

    public function canBeRejected(): bool
    {
        return $this->isPending();
    }

    public function canBeAppliedToPayroll(): bool
    {
        return $this->status === 'approved';
    }

    public static function generateClaimNumber(): string
    {
        return 'EXP-' . date('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function approve(User $reviewer): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        return true;
    }

    public function reject(User $reviewer, string $reason): bool
    {
        if (!$this->canBeRejected()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        return true;
    }

    public function applyToPayroll(Payroll $payroll): bool
    {
        if (!$this->canBeAppliedToPayroll()) {
            return false;
        }

        $this->update([
            'status' => 'applied_to_payroll',
            'applied_to_payroll_id' => $payroll->id,
            'applied_to_payroll_date' => now()->toDateString(),
        ]);

        return true;
    }
}
