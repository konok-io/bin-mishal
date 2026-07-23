<?php

namespace App\Models\Cargo;

use App\Models\CMS\Setting;
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
        'tiered_pricing',
        'flat_rate',
        'currency',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'base_price' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'flat_rate' => 'decimal:2',
        'tiered_pricing' => 'array',
    ];

    // Pricing types
    public const TYPE_FIXED = 'fixed';
    public const TYPE_PER_KG = 'per_kg';
    public const TYPE_TIERED = 'tiered';
    public const TYPE_HYBRID = 'hybrid';

    public const PRICING_TYPES = [
        self::TYPE_FIXED => 'Fixed Package Price',
        self::TYPE_PER_KG => 'Per Kilogram Rate',
        self::TYPE_TIERED => 'Weight Tiers',
        self::TYPE_HYBRID => 'Fixed + Per KG',
    ];

    public const CURRENCY_SAR = 'SAR';
    public const CURRENCY_BDT = 'BDT';

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

    /**
     * Calculate price based on weight and pricing type
     */
    public function calculatePrice(float $weight): float
    {
        return match ($this->pricing_type) {
            self::TYPE_FIXED => $this->flat_rate ?? $this->base_price ?? 0,
            self::TYPE_PER_KG => $this->calculatePerKgPrice($weight),
            self::TYPE_TIERED => $this->calculateTieredPrice($weight),
            self::TYPE_HYBRID => $this->calculateHybridPrice($weight),
            default => $this->base_price ?? 0,
        };
    }

    /**
     * Per kilogram pricing
     */
    protected function calculatePerKgPrice(float $weight): float
    {
        $rate = (float) ($this->price_per_kg ?? 15);
        return $weight * $rate;
    }

    /**
     * Tiered pricing - fixed price for each weight tier
     */
    protected function calculateTieredPrice(float $weight): float
    {
        $tiers = $this->tiered_pricing ?? $this->getDefaultTiers();
        
        foreach ($tiers as $tier) {
            $minWeight = (float) ($tier['min_weight'] ?? 0);
            $maxWeight = (float) ($tier['max_weight'] ?? PHP_FLOAT_MAX);
            
            if ($weight >= $minWeight && $weight <= $maxWeight) {
                return (float) ($tier['price'] ?? $this->flat_rate ?? 0);
            }
        }
        
        // If weight exceeds all tiers, use the last tier's rate + extra per kg
        if (!empty($tiers)) {
            $lastTier = end($tiers);
            $basePrice = (float) ($lastTier['price'] ?? 0);
            $extraRate = (float) ($lastTier['extra_per_kg'] ?? 0);
            $minWeight = (float) ($lastTier['max_weight'] ?? 0);
            
            if ($weight > $minWeight && $extraRate > 0) {
                return $basePrice + (($weight - $minWeight) * $extraRate);
            }
            
            return $basePrice;
        }
        
        return $this->flat_rate ?? $this->base_price ?? 0;
    }

    /**
     * Hybrid pricing - base price + per kg for excess
     */
    protected function calculateHybridPrice(float $weight): float
    {
        $baseWeight = (float) ($this->min_weight ?? 0);
        $basePrice = (float) ($this->base_price ?? 0);
        $rate = (float) ($this->price_per_kg ?? 15);
        
        if ($weight <= $baseWeight) {
            return $basePrice;
        }
        
        $excessWeight = $weight - $baseWeight;
        return $basePrice + ($excessWeight * $rate);
    }

    /**
     * Get default tiered pricing tiers
     */
    protected function getDefaultTiers(): array
    {
        return [
            ['min_weight' => 0, 'max_weight' => 5, 'price' => 100],
            ['min_weight' => 5, 'max_weight' => 10, 'price' => 180],
            ['min_weight' => 10, 'max_weight' => 15, 'price' => 250],
            ['min_weight' => 15, 'max_weight' => 23, 'price' => 300],
            ['min_weight' => 23, 'max_weight' => 30, 'price' => 350],
        ];
    }

    /**
     * Calculate total price including VAT
     */
    public function calculateTotal(float $weight): array
    {
        $subtotal = $this->calculatePrice($weight);
        $vatPercentage = (float) ($this->vat_percentage ?? Setting::getValue('vat_rate', 15));
        $vat = $subtotal * ($vatPercentage / 100);
        $total = $subtotal + $vat;
        
        return [
            'subtotal' => round($subtotal, 2),
            'vat' => round($vat, 2),
            'vat_percentage' => $vatPercentage,
            'total' => round($total, 2),
            'currency' => $this->currency ?? self::CURRENCY_SAR,
            'weight' => $weight,
        ];
    }

    /**
     * Calculate price in alternative currency
     */
    public function calculateInCurrency(float $weight, string $currency = self::CURRENCY_BDT): array
    {
        $result = $this->calculateTotal($weight);
        
        if ($currency === self::CURRENCY_BDT && $result['currency'] === self::CURRENCY_SAR) {
            $exchangeRate = (float) Setting::getValue('exchange_rate', 1);
            $result['subtotal'] = round($result['subtotal'] * $exchangeRate, 2);
            $result['vat'] = round($result['vat'] * $exchangeRate, 2);
            $result['total'] = round($result['total'] * $exchangeRate, 2);
            $result['currency'] = $currency;
            $result['exchange_rate'] = $exchangeRate;
        }
        
        return $result;
    }

    public function calculateVat(float $amount): float
    {
        $vatPercentage = (float) ($this->vat_percentage ?? Setting::getValue('vat_rate', 15));
        return $amount * ($vatPercentage / 100);
    }

    /**
     * Scope for active pricing
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific route
     */
    public function scopeForRoute($query, $originCityId, $destinationCityId)
    {
        return $query->where('origin_city_id', $originCityId)
                     ->where('destination_city_id', $destinationCityId);
    }
}
