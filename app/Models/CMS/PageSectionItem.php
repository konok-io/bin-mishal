<?php

declare(strict_types=1);

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_section_id',
        'title',
        'subtitle',
        'description',
        'icon',
        'image',
        'image_alt',
        'link_text',
        'link_url',
        'link_target',
        'extra',
        'order',
        'status',
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'description' => 'array',
        'link_text' => 'array',
        'extra' => 'array',
        'order' => 'integer',
        'status' => 'boolean',
    ];

    // Relationships
    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Accessors
    public function getTranslatedTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->title[$locale] ?? $this->title['en'] ?? null;
    }

    public function getTranslatedSubtitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->subtitle[$locale] ?? $this->subtitle['en'] ?? null;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->description[$locale] ?? $this->description['en'] ?? null;
    }

    public function getTranslatedLinkTextAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->link_text[$locale] ?? $this->link_text['en'] ?? null;
    }

    /**
     * Resolve the full URL with locale prefix
     */
    public function getResolvedUrlAttribute(): ?string
    {
        if (!$this->link_url) {
            return null;
        }

        $locale = app()->getLocale();

        // External URLs
        if (str_starts_with($this->link_url, ['http://', 'https://', 'mailto:', 'tel:'])) {
            return $this->link_url;
        }

        return "/{$locale}" . ltrim($this->link_url, '/');
    }

    // Get a specific extra field
    public function getExtraField(string $key, mixed $default = null): mixed
    {
        $extra = $this->extra ?? [];

        return $extra[$key] ?? $extra[$key . '_' . app()->getLocale()] ?? $default;
    }
}
