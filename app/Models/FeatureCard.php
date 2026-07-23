<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureCard extends Model
{
    use HasFactory;

    protected $table = 'feature_cards';

    protected $fillable = [
        'title',
        'title_bn',
        'title_ar',
        'icon',
        'number',
        'number_suffix',
        'number_suffix_bn',
        'number_suffix_ar',
        'description',
        'description_bn',
        'description_ar',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'number' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getTitleAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->title_bn ?: $value;
        if ($locale === 'ar') return $this->title_ar ?: $value;
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->description_bn ?: $value;
        if ($locale === 'ar') return $this->description_ar ?: $value;
        return $value;
    }

    public function getNumberSuffixAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->number_suffix_bn ?: $value;
        if ($locale === 'ar') return $this->number_suffix_ar ?: $value;
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order');
    }

    public static function getActive()
    {
        return static::active()->sorted()->get();
    }
}
