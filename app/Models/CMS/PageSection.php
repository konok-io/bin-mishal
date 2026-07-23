<?php

declare(strict_types=1);

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_type',
        'name',
        'content',
        'settings',
        'data_source',
        'visibility',
        'order',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'data_source' => 'array',
        'visibility' => 'array',
        'order' => 'integer',
        'status' => 'boolean',
    ];

    // Relationships
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PageSectionItem::class)->orderBy('order');
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
    public function getContent(string $key, ?string $locale = null): mixed
    {
        $locale = $locale ?? app()->getLocale();
        $content = $this->content ?? [];

        // Try current locale, then English, then first available
        return $content[$key][$locale] 
            ?? $content[$key]['en'] 
            ?? array_values($content[$key] ?? [null])[0] 
            ?? null;
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        $settings = $this->settings ?? [];

        return $settings[$key] ?? $default;
    }

    /**
     * Check if section should be visible
     */
    public function isVisible(): bool
    {
        if (!$this->status) {
            return false;
        }

        $visibility = $this->visibility ?? [];

        // Check locale
        if (!empty($visibility['locales'])) {
            if (!in_array(app()->getLocale(), $visibility['locales'])) {
                return false;
            }
        }

        // Check date range
        if (!empty($visibility['date_from'])) {
            if (now()->lt($visibility['date_from'])) {
                return false;
            }
        }

        if (!empty($visibility['date_to'])) {
            if (now()->gt($visibility['date_to'])) {
                return false;
            }
        }

        // Check auth requirement
        if (!empty($visibility['auth_required'])) {
            if (!$visibility['auth_required'] && auth()->check()) {
                return false;
            }
            if ($visibility['auth_required'] && !auth()->check()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get resolved content (translates all content fields)
     */
    public function getResolvedContent(): array
    {
        $locale = app()->getLocale();
        $content = $this->content ?? [];
        $resolved = [];

        foreach ($content as $key => $value) {
            if (is_array($value)) {
                $resolved[$key] = $value[$locale] ?? $value['en'] ?? array_values($value)[0] ?? '';
            } else {
                $resolved[$key] = $value;
            }
        }

        return $resolved;
    }

    /**
     * Get resolved settings with defaults
     */
    public function getResolvedSettings(): array
    {
        $settings = $this->settings ?? [];

        return array_merge([
            'background' => 'none',
            'background_color' => null,
            'background_image' => null,
            'padding_top' => 'default',
            'padding_bottom' => 'default',
            'container_width' => 'contained',
            'heading_alignment' => 'center',
            'columns_desktop' => 4,
            'columns_tablet' => 2,
            'columns_mobile' => 1,
            'animation_enabled' => true,
            'visible_desktop' => true,
            'visible_mobile' => true,
            'custom_css_class' => null,
            'custom_id' => null,
        ], $settings);
    }

    // Clear parent page cache
    protected static function booted(): void
    {
        static::updated(fn(PageSection $section) => Page::clearCache($section->page_id));
        static::deleted(fn(PageSection $section) => Page::clearCache($section->page_id));
        static::created(fn(PageSection $section) => Page::clearCache($section->page_id));
    }
}
