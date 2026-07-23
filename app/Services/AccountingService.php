<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\LedgerEntry;
use App\Models\Booking;
use App\Models\Payroll;
use App\Models\ExpenseClaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AccountingService
{
    /**
     * Record a booking payment
     */
    public function recordBookingPayment(Booking $booking): void
    {
        if ($booking->payment_status !== 'paid') {
            return;
        }

        // Check if already recorded
        if ($this->hasEntry('booking', $booking->id)) {
            return;
        }

        $currency = $booking->currency ?? 'SAR';
        $amount = $booking->total_amount;
        $exchangeRate = $this->getExchangeRate($currency);
        $amountBase = $amount * $exchangeRate;

        // Determine revenue account based on service type
        $revenueAccount = $this->getRevenueAccount($booking->service_type);

        $entryNumber = LedgerEntry::generateEntryNumber();
        $description = "Payment for Booking #{$booking->booking_number} - {$booking->service_type}";

        // Debit: Cash/Bank (Asset increases)
        LedgerEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $booking->paid_at ?? now(),
            'entry_type' => 'booking_payment',
            'account_id' => $this->getCashAccount()->id,
            'transaction_type' => 'debit',
            'amount' => $amount,
            'currency' => $currency,
            'exchange_rate' => $exchangeRate,
            'amount_base' => $amountBase,
            'description' => $description,
            'reference_type' => 'booking',
            'reference_id' => $booking->id,
            'branch_id' => $booking->branch_id,
            'created_by' => auth()->id(),
        ]);

        // Credit: Revenue (Revenue increases)
        LedgerEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $booking->paid_at ?? now(),
            'entry_type' => 'booking_payment',
            'account_id' => $revenueAccount->id,
            'transaction_type' => 'credit',
            'amount' => $amount,
            'currency' => $currency,
            'exchange_rate' => $exchangeRate,
            'amount_base' => $amountBase,
            'description' => $description,
            'reference_type' => 'booking',
            'reference_id' => $booking->id,
            'branch_id' => $booking->branch_id,
            'created_by' => auth()->id(),
        ]);

        Log::info('Booking payment recorded in ledger', [
            'booking_id' => $booking->id,
            'entry_number' => $entryNumber,
            'amount' => $amount,
        ]);
    }

    /**
     * Record payroll expense
     */
    public function recordPayroll(Payroll $payroll): void
    {
        // Check if already recorded
        if ($this->hasEntry('payroll', $payroll->id)) {
            return;
        }

        $entryNumber = LedgerEntry::generateEntryNumber();
        $date = $payroll->paid_at ?? $payroll->processed_at ?? now();
        $description = "Payroll for Employee - Period " . $payroll->period_start->format('M Y');

        // Salary expense (debit)
        LedgerEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $date,
            'entry_type' => 'payroll',
            'account_id' => $this->getAccountByCode('5000')->id, // Salaries & Wages
            'transaction_type' => 'debit',
            'amount' => $payroll->net_salary,
            'currency' => 'SAR',
            'exchange_rate' => 1,
            'amount_base' => $payroll->net_salary,
            'description' => $description . " (Net Pay)",
            'reference_type' => 'payroll',
            'reference_id' => $payroll->id,
            'branch_id' => $payroll->employee?->branch_id,
            'created_by' => auth()->id(),
        ]);

        // Cash/Bank (credit)
        LedgerEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $date,
            'entry_type' => 'payroll',
            'account_id' => $this->getCashAccount()->id,
            'transaction_type' => 'credit',
            'amount' => $payroll->net_salary,
            'currency' => 'SAR',
            'exchange_rate' => 1,
            'amount_base' => $payroll->net_salary,
            'description' => $description . " (Net Pay)",
            'reference_type' => 'payroll',
            'reference_id' => $payroll->id,
            'branch_id' => $payroll->employee?->branch_id,
            'created_by' => auth()->id(),
        ]);

        Log::info('Payroll recorded in ledger', [
            'payroll_id' => $payroll->id,
            'entry_number' => $entryNumber,
            'amount' => $payroll->net_salary,
        ]);
    }

    /**
     * Record expense reimbursement
     */
    public function recordExpenseReimbursement(ExpenseClaim $claim): void
    {
        if (!$claim->isReimbursable() || $claim->status !== 'applied_to_payroll') {
            return;
        }

        // Check if already recorded
        if ($this->hasEntry('expense', $claim->id, 'expense_reimbursement')) {
            return;
        }

        $entryNumber = LedgerEntry::generateEntryNumber();
        $description = "Expense Reimbursement - {$claim->claim_number}";

        // Expense account (debit)
        LedgerEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $claim->applied_to_payroll_date ?? now(),
            'entry_type' => 'expense_reimbursement',
            'account_id' => $this->getAccountByCode('5040')->id, // Travel & Transportation
            'transaction_type' => 'debit',
            'amount' => $claim->amount,
            'currency' => $claim->currency,
            'exchange_rate' => $this->getExchangeRate($claim->currency),
            'amount_base' => $claim->amount * $this->getExchangeRate($claim->currency),
            'description' => $description,
            'reference_type' => 'expense',
            'reference_id' => $claim->id,
            'branch_id' => $claim->employee?->branch_id,
            'created_by' => auth()->id(),
        ]);

        // Cash/Bank (credit)
        LedgerEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $claim->applied_to_payroll_date ?? now(),
            'entry_type' => 'expense_reimbursement',
            'account_id' => $this->getCashAccount()->id,
            'transaction_type' => 'credit',
            'amount' => $claim->amount,
            'currency' => $claim->currency,
            'exchange_rate' => $this->getExchangeRate($claim->currency),
            'amount_base' => $claim->amount * $this->getExchangeRate($claim->currency),
            'description' => $description,
            'reference_type' => 'expense',
            'reference_id' => $claim->id,
            'branch_id' => $claim->employee?->branch_id,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Record manual income
     */
    public function recordManualIncome(array $data): LedgerEntry
    {
        $entry = LedgerEntry::create([
            'entry_number' => LedgerEntry::generateEntryNumber(),
            'entry_date' => $data['entry_date'] ?? now(),
            'entry_type' => 'manual_income',
            'account_id' => $data['account_id'],
            'transaction_type' => 'credit',
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'SAR',
            'exchange_rate' => $this->getExchangeRate($data['currency'] ?? 'SAR'),
            'amount_base' => $data['amount'] * $this->getExchangeRate($data['currency'] ?? 'SAR'),
            'description' => $data['description'],
            'notes' => $data['notes'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Also create the debit entry for cash
        LedgerEntry::create([
            'entry_number' => $entry->entry_number,
            'entry_date' => $entry->entry_date,
            'entry_type' => 'manual_income',
            'account_id' => $this->getCashAccount()->id,
            'transaction_type' => 'debit',
            'amount' => $entry->amount,
            'currency' => $entry->currency,
            'exchange_rate' => $entry->exchange_rate,
            'amount_base' => $entry->amount_base,
            'description' => $entry->description,
            'notes' => $entry->notes,
            'branch_id' => $entry->branch_id,
            'created_by' => auth()->id(),
        ]);

        return $entry;
    }

    /**
     * Record manual expense
     */
    public function recordManualExpense(array $data): LedgerEntry
    {
        $entry = LedgerEntry::create([
            'entry_number' => LedgerEntry::generateEntryNumber(),
            'entry_date' => $data['entry_date'] ?? now(),
            'entry_type' => 'manual_expense',
            'account_id' => $data['account_id'],
            'transaction_type' => 'debit',
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'SAR',
            'exchange_rate' => $this->getExchangeRate($data['currency'] ?? 'SAR'),
            'amount_base' => $data['amount'] * $this->getExchangeRate($data['currency'] ?? 'SAR'),
            'description' => $data['description'],
            'notes' => $data['notes'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Also create the credit entry for cash
        LedgerEntry::create([
            'entry_number' => $entry->entry_number,
            'entry_date' => $entry->entry_date,
            'entry_type' => 'manual_expense',
            'account_id' => $this->getCashAccount()->id,
            'transaction_type' => 'credit',
            'amount' => $entry->amount,
            'currency' => $entry->currency,
            'exchange_rate' => $entry->exchange_rate,
            'amount_base' => $entry->amount_base,
            'description' => $entry->description,
            'notes' => $entry->notes,
            'branch_id' => $entry->branch_id,
            'created_by' => auth()->id(),
        ]);

        return $entry;
    }

    /**
     * Generate P&L Report
     */
    public function getProfitLossReport(Carbon $startDate, Carbon $endDate, ?int $branchId = null): array
    {
        $query = LedgerEntry::byDateRange($startDate, $endDate);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $entries = $query->with('account')->get();

        $revenue = $entries->filter(fn($e) => $e->account->type === 'revenue');
        $expenses = $entries->filter(fn($e) => $e->account->type === 'expense');

        $totalRevenue = $revenue->where('transaction_type', 'credit')->sum('amount_base')
            - $revenue->where('transaction_type', 'debit')->sum('amount_base');

        $totalExpenses = $expenses->where('transaction_type', 'debit')->sum('amount_base')
            - $expenses->where('transaction_type', 'credit')->sum('amount_base');

        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'revenue' => [
                'total' => $totalRevenue,
                'by_category' => $this->groupByAccount($revenue, 'credit'),
            ],
            'expenses' => [
                'total' => $totalExpenses,
                'by_category' => $this->groupByAccount($expenses, 'debit'),
            ],
            'net_profit' => $totalRevenue - $totalExpenses,
            'profit_margin' => $totalRevenue > 0 ? (($totalRevenue - $totalExpenses) / $totalRevenue) * 100 : 0,
        ];
    }

    /**
     * Get account balance summary
     */
    public function getAccountBalances(): array
    {
        $accounts = ChartOfAccount::active()->with('ledgerEntries')->get();

        return $accounts->map(function ($account) {
            return [
                'id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'category' => $account->category,
                'balance' => $account->balance,
            ];
        })->toArray();
    }

    /**
     * Initialize system accounts
     */
    public function initializeSystemAccounts(): int
    {
        $count = 0;
        
        foreach (ChartOfAccount::getSystemAccounts() as $accountData) {
            ChartOfAccount::updateOrCreate(
                ['code' => $accountData['code']],
                [
                    'name' => $accountData['name'],
                    'type' => $accountData['type'],
                    'category' => $accountData['category'],
                    'normal_balance' => $accountData['normal_balance'],
                    'is_system' => true,
                    'is_active' => true,
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Helper: Get cash/bank account
     */
    protected function getCashAccount(): ChartOfAccount
    {
        return ChartOfAccount::where('code', '1010')->first();
    }

    /**
     * Helper: Get revenue account by service type
     */
    protected function getRevenueAccount(string $serviceType): ChartOfAccount
    {
        $code = match($serviceType) {
            'flight' => '4000',
            'umrah' => '4010',
            'visa' => '4020',
            'cargo' => '4030',
            'investment' => '4040',
            default => '4100',
        };

        return ChartOfAccount::where('code', $code)->first();
    }

    /**
     * Helper: Get account by code
     */
    protected function getAccountByCode(string $code): ChartOfAccount
    {
        return ChartOfAccount::where('code', $code)->first();
    }

    /**
     * Helper: Check if entry exists
     */
    protected function hasEntry(string $type, int $id, ?string $entryType = null): bool
    {
        $query = LedgerEntry::where('reference_type', $type)
            ->where('reference_id', $id);

        if ($entryType) {
            $query->where('entry_type', $entryType);
        }

        return $query->exists();
    }

    /**
     * Helper: Get exchange rate
     */
    protected function getExchangeRate(string $currency): float
    {
        if ($currency === 'SAR') {
            return 1.0;
        }

        // Get from settings or default
        return (float) \App\Models\Setting::get('exchange_rate_' . $currency, 1.0);
    }

    /**
     * Helper: Group entries by account
     */
    protected function groupByAccount($entries, string $primaryType): array
    {
        return $entries->filter(fn($e) => $e->transaction_type === $primaryType)
            ->groupBy('account_id')
            ->map(function ($group, $accountId) {
                $account = $group->first()->account;
                return [
                    'account_id' => $accountId,
                    'code' => $account->code,
                    'name' => $account->name,
                    'total' => $group->sum('amount_base'),
                ];
            })->values()->toArray();
    }
}
