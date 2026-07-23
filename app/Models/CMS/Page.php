<?php

declare(strict_types=1);

namespace App\Models\CMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'template',
        'is_homepage',
        'is_system',
        'show_header',
        'show_footer',
        'show_breadcrumb',
        'hero_type',
        'hero_image',
        'hero_title',
        'hero_subtitle',
        'hero_video_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
        'noindex',
        'schema_type',
        'layout',
        'custom_css',
        'custom_js',
        'order',
        'status',
        'published_at',
        'scheduled_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'title' => 'array',
        'slug' => 'array',
        'hero_title' => 'array',
        'hero_subtitle' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'is_homepage' => 'boolean',
        'is_system' => 'boolean',
        'show_header' => 'boolean',
        'show_footer' => 'boolean',
        'show_breadcrumb' => 'boolean',
        'noindex' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'order' => 'integer',
    ];

    // Templates
    public const TEMPLATE_DEFAULT = 'default';
    public const TEMPLATE_FULL_WIDTH = 'full_width';
    public const TEMPLATE_SIDEBAR = 'sidebar';
    public const TEMPLATE_LANDING = 'landing';
    public const TEMPLATE_CONTACT = 'contact';
    public const TEMPLATE_LISTING = 'listing';

    public const TEMPLATES = [
        self::TEMPLATE_DEFAULT => 'Default Page',
        self::TEMPLATE_FULL_WIDTH => 'Full Width',
        self::TEMPLATE_SIDEBAR => 'With Sidebar',
        self::TEMPLATE_LANDING => 'Landing Page',
        self::TEMPLATE_CONTACT => 'Contact Page',
        self::TEMPLATE_LISTING => 'Listing Page',
    ];

    // Hero types
    public const HERO_NONE = 'none';
    public const HERO_IMAGE = 'image';
    public const HERO_SLIDER = 'slider';
    public const HERO_VIDEO = 'video';
    public const HERO_GRADIENT = 'gradient';

    public const HERO_TYPES = [
        self::HERO_NONE => 'No Hero',
        self::HERO_IMAGE => 'Image Hero',
        self::HERO_SLIDER => 'Slider Hero',
        self::HERO_VIDEO => 'Video Hero',
        self::HERO_GRADIENT => 'Gradient Hero',
    ];

    // Status
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';

    private const CACHE_TTL = 3600;

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->where('status', 'published')
            ->orderBy('order');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class)->orderByDesc('version_number');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeVisible($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_PUBLISHED)
              ->orWhere('status', self::STATUS_SCHEDULED);
        });
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    // Accessors
    public function getTranslatedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->title[$locale] ?? $this->title['en'] ?? '';
    }

    public function getTranslatedSlugAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->slug[$locale] ?? $this->slug['en'] ?? '';
    }

    public function getSlug(string $locale): ?string
    {
        return $this->slug[$locale] ?? $this->slug['en'] ?? null;
    }

    public function getUrl(string $locale): string
    {
        if ($this->is_homepage) {
            return "/{$locale}";
        }

        $slug = $this->getSlug($locale);
        $parentSlug = $this->parent?->getSlug($locale);

        if ($parentSlug) {
            return "/{$locale}/{$parentSlug}/{$slug}";
        }

        return "/{$locale}/{$slug}";
    }

    public function getMetaTitle(string $locale): ?string
    {
        $title = $this->meta_title[$locale] ?? $this->meta_title['en'] ?? null;

        return $title ?: $this->title[$locale] ?? $this->title['en'] ?? null;
    }

    public function getMetaDescription(string $locale): ?string
    {
        return $this->meta_description[$locale] ?? $this->meta_description['en'] ?? null;
    }

    // Check if page is currently visible
    public function isVisible(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED && $this->status !== self::STATUS_SCHEDULED) {
            return false;
        }

        if ($this->status === self::STATUS_SCHEDULED && $this->scheduled_at && $this->scheduled_at->isFuture()) {
            return false;
        }

        return true;
    }

    // Get full page with sections, cached
    public function getWithSections(): self
    {
        $cacheKey = "page_{$this->id}_sections_" . app()->getLocale();

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return $this->load(['sections' => function ($q) {
                $q->where('status', true)->orderBy('order')
                  ->with(['items' => fn($iq) => $iq->where('status', true)->orderBy('order')]);
            }]);
        });
    }

    // Static find by slug
    public static function findBySlug(string $slug, string $locale): ?self
    {
        return self::visible()
            ->where("slug->{$locale}", $slug)
            ->orWhere("slug->en", $slug)
            ->first();
    }

    // Get homepage
    public static function getHomepage(): ?self
    {
        return Cache::remember('homepage_page', self::CACHE_TTL, function () {
            return self::homepage()->visible()->first();
        });
    }

    // Clear cache
    public static function clearCache(?int $pageId = null): void
    {
        if ($pageId) {
            foreach (['bn', 'en', 'ar'] as $locale) {
                Cache::forget("page_{$pageId}_sections_{$locale}");
            }
        }

        Cache::forget('homepage_page');
    }

    protected static function booted(): void
    {
        static::updated(function (Page $page) {
            self::clearCache($page->id);
        });

        static::deleted(function (Page $page) {
            self::clearCache($page->id);
        });
    }

    // Media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')->singleFile();
        $this->addMediaCollection('og_image')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
