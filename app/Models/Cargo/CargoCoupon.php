<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->startOfDay();
        
        if ($this->valid_from && $today->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $today->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderAmount)
    {
        if (!$this->isValid() || $orderAmount < $this->min_order_amount) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            
            return $discount;
        }

        return min($this->discount_value, $orderAmount);
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
