<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestorService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_key',
        'name',
        'name_bn',
        'name_ar',
        'description',
        'description_bn',
        'description_ar',
        'icon',
        'color',
        'required_documents',
        'fee_structure',
        'processing_time',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'required_documents' => 'array',
        'fee_structure' => 'array',
    ];

    // Service keys
    public const SERVICE_MISA_LICENSE = 'misa_license';
    public const SERVICE_FOREIGN_INVESTMENT = 'foreign_investment';
    public const SERVICE_COMPANY_REGISTRATION = 'company_registration';
    public const SERVICE_BRANCH_REGISTRATION = 'branch_registration';
    public const SERVICE_INVESTOR_CONSULTATION = 'investor_consultation';

    public const SERVICES = [
        self::SERVICE_MISA_LICENSE => 'MISA License',
        self::SERVICE_FOREIGN_INVESTMENT => 'Foreign Investment',
        self::SERVICE_COMPANY_REGISTRATION => 'Company Registration',
        self::SERVICE_BRANCH_REGISTRATION => 'Branch Registration',
        self::SERVICE_INVESTOR_CONSULTATION => 'Investor Consultation',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(InvestorApplication::class, 'service_id');
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

    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') {
            return $this->description_bn ?: $value;
        } elseif ($locale === 'ar') {
            return $this->description_ar ?: $value;
        }
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
