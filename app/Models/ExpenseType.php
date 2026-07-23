<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    use HasFactory;

    protected $table = 'expense_types';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'payment_type',
        'max_amount',
        'requires_receipt',
        'requires_approval',
        'approval_level',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'max_amount' => 'decimal:2',
        'requires_receipt' => 'boolean',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const CATEGORIES = [
        'travel' => 'Travel',
        'food' => 'Food & Meals',
        'transport' => 'Transport & Fuel',
        'equipment' => 'Equipment & Supplies',
        'communication' => 'Communication',
        'other' => 'Other',
    ];

    public const PAYMENT_TYPES = [
        'reimbursable' => 'Reimbursable (Added to Payroll)',
        'deductible' => 'Deductible (Subtracted from Payroll)',
        'both' => 'Both Options',
    ];

    public function claims(): HasMany
    {
        return $this->hasMany(ExpenseClaim::class, 'expense_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeReimbursable($query)
    {
        return $query->whereIn('payment_type', ['reimbursable', 'both']);
    }

    public function scopeDeductible($query)
    {
        return $query->whereIn('payment_type', ['deductible', 'both']);
    }

    public function isReimbursable(): bool
    {
        return in_array($this->payment_type, ['reimbursable', 'both']);
    }

    public function isDeductible(): bool
    {
        return in_array($this->payment_type, ['deductible', 'both']);
    }
}
