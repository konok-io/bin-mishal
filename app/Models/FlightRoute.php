<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightRoute extends Model
{
    use HasFactory;

    protected $table = 'flight_routes';

    protected $fillable = [
        'from_city',
        'from_city_bn',
        'from_city_ar',
        'from_country',
        'to_city',
        'to_city_bn',
        'to_city_ar',
        'to_country',
        'price',
        'currency',
        'airline',
        'image_url',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function getFromCityAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->from_city_bn ?: $value;
        if ($locale === 'ar') return $this->from_city_ar ?: $value;
        return $value;
    }

    public function getToCityAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->to_city_bn ?: $value;
        if ($locale === 'ar') return $this->to_city_ar ?: $value;
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('is_featured', 'desc')->orderBy('sort_order');
    }

    public static function getActive()
    {
        return static::active()->sorted()->get();
    }
}
