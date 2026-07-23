<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_bn',
        'title_ar',
        'slug',
        'department',
        'department_bn',
        'department_ar',
        'location',
        'location_bn',
        'location_ar',
        'country',
        'employment_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_visible',
        'description',
        'description_bn',
        'description_ar',
        'responsibilities',
        'responsibilities_bn',
        'responsibilities_ar',
        'requirements',
        'requirements_bn',
        'requirements_ar',
        'benefits',
        'benefits_bn',
        'benefits_ar',
        'deadline',
        'status',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_visible' => 'boolean',
        'is_featured' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . time();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CLOSED = 'closed';

    public const EMPLOYMENT_TYPES = [
        'full_time' => 'Full-time',
        'part_time' => 'Part-time',
        'contract' => 'Contract',
        'internship' => 'Internship',
        'remote' => 'Remote',
    ];

    public const EXPERIENCE_LEVELS = [
        'entry' => 'Entry Level',
        'mid' => 'Mid Level',
        'senior' => 'Senior Level',
        'lead' => 'Lead/Manager',
        'executive' => 'Executive',
    ];

    public const DEPARTMENTS = [
        'operations' => 'Operations',
        'sales' => 'Sales & Marketing',
        'finance' => 'Finance & Accounting',
        'hr' => 'Human Resources',
        'it' => 'IT & Technology',
        'legal' => 'Legal & Compliance',
        'customer_service' => 'Customer Service',
        'travel' => 'Travel & Tourism',
        'management' => 'Management',
    ];

    public function getTitleAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->title_bn ?: $value;
        if ($locale === 'ar') return $this->title_ar ?: $value;
        return $value;
    }

    public function getDepartmentAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->department_bn ?: $value;
        if ($locale === 'ar') return $this->department_ar ?: $value;
        return $value;
    }

    public function getLocationAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->location_bn ?: $value;
        if ($locale === 'ar') return $this->location_ar ?: $value;
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->description_bn ?: $value;
        if ($locale === 'ar') return $this->description_ar ?: $value;
        return $value;
    }

    public function getResponsibilitiesAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->responsibilities_bn ?: $value;
        if ($locale === 'ar') return $this->responsibilities_ar ?: $value;
        return $value;
    }

    public function getRequirementsAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->requirements_bn ?: $value;
        if ($locale === 'ar') return $this->requirements_ar ?: $value;
        return $value;
    }

    public function getBenefitsAttribute($value)
    {
        $locale = app()->getLocale();
        if ($locale === 'bn') return $this->benefits_bn ?: $value;
        if ($locale === 'ar') return $this->benefits_ar ?: $value;
        return $value;
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhere('deadline', '>=', now()->toDateString());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && ($this->deadline === null || $this->deadline->gte(now()));
    }
}
