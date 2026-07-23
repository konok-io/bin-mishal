<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoPricing extends Model
{
    use HasFactory;

    protected $table = 'cargo_pricing';

    protected $fillable = [
        'cargo_type_id',
        'origin_city_id',
        'destination_city_id',
        'pricing_type',
        'min_weight',
        'max_weight',
        'price_per_kg',
        'base_price',
        'vat_percentage',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'base_price' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
    ];

    public function cargoType(): BelongsTo
    {
        return $this->belongsTo(CargoType::class, 'cargo_type_id');
    }

    public function originCity(): BelongsTo
    {
        return $this->belongsTo(CargoCity::class, 'origin_city_id');
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(CargoCity::class, 'destination_city_id');
    }

    public function calculatePrice($weight)
    {
        $cost = $this->base_price;
        
        if ($this->pricing_type === 'weight' && $weight > 0) {
            $cost += ($weight * $this->price_per_kg);
        }
        
        return $cost;
    }

    public function calculateVat($amount)
    {
        return ($amount * $this->vat_percentage) / 100;
    }
}
