<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'name_bn',
        'name_ar',
        'delivery_charge',
        'min_delivery_days',
        'max_delivery_days',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delivery_charge' => 'decimal:2',
        'min_delivery_days' => 'integer',
        'max_delivery_days' => 'integer',
        'sort_order' => 'integer',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(CargoCity::class, 'city_id');
    }

    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->name_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->name_ar ?: $value;
        }
        return $value;
    }
}
