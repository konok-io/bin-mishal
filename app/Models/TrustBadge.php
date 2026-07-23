<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrustBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'image_url',
        'link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->name_bn ?: $value;
        if ($locale === 'ar') return $this->name_ar ?: $value;
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
