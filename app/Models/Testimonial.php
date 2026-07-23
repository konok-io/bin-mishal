<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'designation',
        'designation_bn',
        'designation_ar',
        'company',
        'company_bn',
        'company_ar',
        'quote',
        'quote_bn',
        'quote_ar',
        'rating',
        'avatar',
        'service_type',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
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

    public function getDesignationAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->designation_bn ?: $value;
        if ($locale === 'ar') return $this->designation_ar ?: $value;
        return $value;
    }

    public function getCompanyAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->company_bn ?: $value;
        if ($locale === 'ar') return $this->company_ar ?: $value;
        return $value;
    }

    public function getQuoteAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->quote_bn ?: $value;
        if ($locale === 'ar') return $this->quote_ar ?: $value;
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
}
