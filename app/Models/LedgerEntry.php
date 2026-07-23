<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerEntry extends Model
{
    use HasFactory;

    protected $table = 'ledger_entries';

    public $timestamps = false;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'entry_type',
        'account_id',
        'transaction_type',
        'amount',
        'currency',
        'exchange_rate',
        'amount_base',
        'description',
        'reference_type',
        'reference_id',
        'branch_id',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'amount_base' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public const ENTRY_TYPES = [
        'booking_payment' => 'Booking Payment',
        'cargo_payment' => 'Cargo Payment',
        'visa_payment' => 'Visa Payment',
        'payroll' => 'Payroll',
        'expense_reimbursement' => 'Expense Reimbursement',
        'expense_deduction' => 'Expense Deduction',
        'manual_income' => 'Manual Income',
        'manual_expense' => 'Manual Expense',
        'refund' => 'Refund',
        'adjustment' => 'Adjustment',
    ];

    public const TRANSACTION_TYPES = [
        'debit' => 'Debit',
        'credit' => 'Credit',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('entry_type', $type);
    }

    public function scopeByAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeDebits($query)
    {
        return $query->where('transaction_type', 'debit');
    }

    public function scopeCredits($query)
    {
        return $query->where('transaction_type', 'credit');
    }

    public function scopeRevenue($query)
    {
        return $query->whereHas('account', fn($q) => $q->where('type', 'revenue'));
    }

    public function scopeExpenses($query)
    {
        return $query->whereHas('account', fn($q) => $q->where('type', 'expense'));
    }

    public static function generateEntryNumber(): string
    {
        return 'LED-' . date('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function getReferenceModelAttribute(): ?Model
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        $modelClass = match($this->reference_type) {
            'booking' => \App\Models\Booking::class,
            'cargo' => \App\Models\Cargo::class,
            'visa' => \App\Models\VisaApplication::class,
            'payroll' => \App\Models\Payroll::class,
            'expense' => \App\Models\ExpenseClaim::class,
            default => null,
        };

        if ($modelClass) {
            return $modelClass::find($this->reference_id);
        }

        return null;
    }
}
