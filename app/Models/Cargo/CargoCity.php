<?php

namespace App\Models\Cargo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargoCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'name_bn',
        'name_ar',
        'code',
        'latitude',
        'longitude',
        'is_active',
        'is_saudi',
        'is_bangladesh',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_saudi' => 'boolean',
        'is_bangladesh' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sort_order' => 'integer',
    ];

    public function zones(): HasMany
    {
        return $this->hasMany(CargoZone::class, 'city_id');
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSaudi($query)
    {
        return $query->where('is_saudi', true);
    }

    public function scopeBangladesh($query)
    {
        return $query->where('is_bangladesh', true);
    }
}
