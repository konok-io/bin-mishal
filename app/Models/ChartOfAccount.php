<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'category',
        'normal_balance',
        'parent_id',
        'is_active',
        'is_system',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const TYPES = [
        'asset' => 'Asset',
        'liability' => 'Liability',
        'equity' => 'Equity',
        'revenue' => 'Revenue',
        'expense' => 'Expense',
    ];

    public const CATEGORIES = [
        'current_asset' => 'Current Asset',
        'fixed_asset' => 'Fixed Asset',
        'current_liability' => 'Current Liability',
        'long_term_liability' => 'Long Term Liability',
        'owner_equity' => 'Owner Equity',
        'operating_revenue' => 'Operating Revenue',
        'non_operating_revenue' => 'Non-Operating Revenue',
        'operating_expense' => 'Operating Expense',
        'non_operating_expense' => 'Non-Operating Expense',
    ];

    public const NORMAL_BALANCES = [
        'debit' => 'Debit',
        'credit' => 'Credit',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class, 'account_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAssets($query)
    {
        return $query->where('type', 'asset');
    }

    public function scopeLiabilities($query)
    {
        return $query->where('type', 'liability');
    }

    public function scopeRevenue($query)
    {
        return $query->where('type', 'revenue');
    }

    public function scopeExpenses($query)
    {
        return $query->where('type', 'expense');
    }

    public function getBalanceAttribute(): float
    {
        $debits = $this->ledgerEntries()->where('transaction_type', 'debit')->sum('amount_base');
        $credits = $this->ledgerEntries()->where('transaction_type', 'credit')->sum('amount_base');

        if ($this->normal_balance === 'debit') {
            return $debits - $credits;
        }
        
        return $credits - $debits;
    }

    public function getDebitTotalAttribute(): float
    {
        return (float) $this->ledgerEntries()->where('transaction_type', 'debit')->sum('amount_base');
    }

    public function getCreditTotalAttribute(): float
    {
        return (float) $this->ledgerEntries()->where('transaction_type', 'credit')->sum('amount_base');
    }

    public static function getSystemAccounts(): array
    {
        return [
            // Assets
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit'],
            ['code' => '1010', 'name' => 'Bank Accounts', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit'],
            ['code' => '1100', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'current_asset', 'normal_balance' => 'debit'],
            
            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit'],
            ['code' => '2010', 'name' => 'Salaries Payable', 'type' => 'liability', 'category' => 'current_liability', 'normal_balance' => 'credit'],
            
            // Equity
            ['code' => '3000', 'name' => 'Owner Capital', 'type' => 'equity', 'category' => 'owner_equity', 'normal_balance' => 'credit'],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'category' => 'owner_equity', 'normal_balance' => 'credit'],
            
            // Revenue
            ['code' => '4000', 'name' => 'Flight Booking Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit'],
            ['code' => '4010', 'name' => 'Umrah Package Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit'],
            ['code' => '4020', 'name' => 'Visa Service Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit'],
            ['code' => '4030', 'name' => 'Cargo Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit'],
            ['code' => '4040', 'name' => 'Investment Service Revenue', 'type' => 'revenue', 'category' => 'operating_revenue', 'normal_balance' => 'credit'],
            ['code' => '4100', 'name' => 'Other Income', 'type' => 'revenue', 'category' => 'non_operating_revenue', 'normal_balance' => 'credit'],
            
            // Expenses
            ['code' => '5000', 'name' => 'Salaries & Wages', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5010', 'name' => 'Employee Benefits', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5020', 'name' => 'Rent & Utilities', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5030', 'name' => 'Marketing & Advertising', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5040', 'name' => 'Travel & Transportation', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5050', 'name' => 'Office Supplies', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5060', 'name' => 'Professional Fees', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5070', 'name' => 'Cargo Operations Cost', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5080', 'name' => 'PSP Transaction Fees', 'type' => 'expense', 'category' => 'operating_expense', 'normal_balance' => 'debit'],
            ['code' => '5100', 'name' => 'Other Expenses', 'type' => 'expense', 'category' => 'non_operating_expense', 'normal_balance' => 'debit'],
        ];
    }
}
