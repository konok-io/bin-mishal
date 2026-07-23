<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'label_bn',
        'label_ar',
        'value',
        'suffix',
        'suffix_bn',
        'suffix_ar',
        'prefix',
        'prefix_bn',
        'prefix_ar',
        'icon',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getLabelAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->label_bn ?: $value;
        if ($locale === 'ar') return $this->label_ar ?: $value;
        return $value;
    }

    public function getSuffixAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->suffix_bn ?: $value;
        if ($locale === 'ar') return $this->suffix_ar ?: $value;
        return $value;
    }

    public function getPrefixAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->prefix_bn ?: $value;
        if ($locale === 'ar') return $this->prefix_ar ?: $value;
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
