<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'basic_salary',
        'allowances',
        'deductions',
        'bonus',
        'late_days',
        'late_deduction',
        'net_salary',
        'status',
        'processed_by',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'allowances' => 'array',
        'deductions' => 'array',
    ];

    public const STATUSES = [
        'draft' => 'Draft',
        'processed' => 'Processed',
        'approved' => 'Approved',
        'paid' => 'Paid',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getTotalEarningsAttribute(): float
    {
        return $this->basic_salary + array_sum($this->allowances ?? []) + ($this->bonus ?? 0);
    }

    public function getTotalDeductionsAttribute(): float
    {
        return array_sum($this->deductions ?? []) + ($this->late_deduction ?? 0);
    }
}
