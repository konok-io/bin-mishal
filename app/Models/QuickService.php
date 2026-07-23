<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickService extends Model
{
    use HasFactory;

    protected $table = 'quick_services';

    protected $fillable = [
        'title',
        'title_bn',
        'title_ar',
        'icon',
        'description',
        'description_bn',
        'description_ar',
        'link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
