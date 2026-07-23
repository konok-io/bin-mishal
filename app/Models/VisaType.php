<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class VisaType extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'name_bn',
        'name_ar',
        'slug',
        'country',
        'category',
        'description',
        'description_bn',
        'description_ar',
        'processing_days',
        'government_fee',
        'service_fee',
        'total_fee',
        'required_documents',
        'eligibility_criteria',
        'icon',
        'is_featured',
        'status',
    ];

    public array $translatable = ['name', 'name_bn', 'name_ar', 'description', 'description_bn', 'description_ar'];

    protected $casts = [
        'government_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'required_documents' => 'array',
        'eligibility_criteria' => 'array',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function applications(): HasMany
    {
        return $this->hasMany(VisaApplication::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
