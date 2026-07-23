<?php

declare(strict_types=1);

namespace App\Models\CMS;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'description',
        'type',
        'url',
        'route_name',
        'route_params',
        'page_id',
        'icon',
        'target',
        'css_class',
        'badge_text',
        'badge_color',
        'is_mega',
        'mega_column',
        'order',
        'status',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'route_params' => 'array',
        'badge_text' => 'array',
        'is_mega' => 'boolean',
        'mega_column' => 'integer',
        'order' => 'integer',
        'status' => 'boolean',
    ];

    public const TYPE_INTERNAL = 'internal';
    public const TYPE_EXTERNAL = 'external';
    public const TYPE_ROUTE = 'route';
    public const TYPE_PAGE = 'page';
    public const TYPE_CATEGORY = 'category';
    public const TYPE_CUSTOM = 'custom';

    public const TYPES = [
        self::TYPE_INTERNAL => 'Internal Link',
        self::TYPE_EXTERNAL => 'External URL',
        self::TYPE_ROUTE => 'Named Route',
        self::TYPE_PAGE => 'CMS Page',
        self::TYPE_CATEGORY => 'Category',
        self::TYPE_CUSTOM => 'Custom URL',
    ];

    // Relationships
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('status', true)
            ->orderBy('order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Accessors
    public function getTranslatedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $title = $this->title[$locale] ?? $this->title['en'] ?? '';

        return $title;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $description = $this->description[$locale] ?? $this->description['en'] ?? null;

        return $description;
    }

    public function getTranslatedBadgeTextAttribute(): ?string
    {
        if (!$this->badge_text) {
            return null;
        }

        $locale = app()->getLocale();

        return $this->badge_text[$locale] ?? $this->badge_text['en'] ?? null;
    }

    /**
     * Resolve URL based on type and current locale
     */
    public function resolveUrl(): ?string
    {
        $locale = app()->getLocale();

        return match ($this->type) {
            self::TYPE_INTERNAL => $this->url ? "/{$locale}" . ltrim($this->url, '/') : null,
            self::TYPE_EXTERNAL => $this->url,
            self::TYPE_ROUTE => $this->route_name ? $this->buildRouteUrl($locale) : null,
            self::TYPE_PAGE => $this->buildPageUrl($locale),
            self::TYPE_CATEGORY => $this->url ? "/{$locale}" . ltrim($this->url, '/') : null,
            self::TYPE_CUSTOM => $this->url ? "/{$locale}" . ltrim($this->url, '/') : null,
            default => null,
        };
    }

    protected function buildRouteUrl(string $locale): ?string
    {
        if (!$this->route_name) {
            return null;
        }

        try {
            $params = $this->route_params ?? [];
            $params['locale'] = $locale;

            return route($this->route_name, $params);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function buildPageUrl(string $locale): ?string
    {
        if (!$this->page_id || !$this->page) {
            return null;
        }

        $slug = $this->page->getSlug($locale);

        if (!$slug) {
            return null;
        }

        $parentSlug = $this->page->parent?->getSlug($locale);

        if ($parentSlug) {
            return "/{$locale}/{$parentSlug}/{$slug}";
        }

        return "/{$locale}/{$slug}";
    }

    /**
     * Check if this item is active based on current URL
     */
    public function isActive(): bool
    {
        $currentUrl = request()->path();
        $itemUrl = $this->resolveUrl();

        if (!$itemUrl) {
            return false;
        }

        // Remove locale prefix for comparison
        $currentPath = preg_replace('/^(bn|en|ar)\//', '', $currentUrl);
        $itemPath = preg_replace('/^(bn|en|ar)\//', '', parse_url($itemUrl, PHP_URL_PATH) ?? '');

        return $currentPath === $itemPath || str_starts_with($currentPath, $itemPath . '/');
    }

    // Clear parent menu cache on update
    protected static function booted(): void
    {
        static::updated(function (MenuItem $item) {
            Menu::clearCache($item->menu_id);
        });

        static::deleted(function (MenuItem $item) {
            Menu::clearCache($item->menu_id);
        });

        static::created(function (MenuItem $item) {
            Menu::clearCache($item->menu_id);
        });
    }
}
