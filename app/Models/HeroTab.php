<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HeroTab extends Model
{
    use HasFactory;

    protected $fillable = [
        'tab_key',
        'label',
        'title',
        'subtitle',
        'description',
        'icon',
        'image',
        'features',
        'button_text',
        'button_url',
        'route_name',
        'route_params',
        'service_type',
        'order',
        'is_active',
        'show_in_nav',
    ];

    protected $casts = [
        'label' => 'array',
        'title' => 'array',
        'subtitle' => 'array',
        'description' => 'array',
        'features' => 'array',
        'button_text' => 'array',
        'route_params' => 'array',
        'is_active' => 'boolean',
        'show_in_nav' => 'boolean',
        'order' => 'integer',
    ];

    // Default tab keys
    public const TAB_FLIGHT = 'flight';
    public const TAB_UMRAH = 'umrah';
    public const TAB_VISA = 'visa';
    public const TAB_CARGO = 'cargo';
    public const TAB_APPOINTMENT = 'appointment';
    public const TAB_INVESTOR = 'investor';

    // Service types for booking
    public const SERVICE_FLIGHT = 'flight';
    public const SERVICE_UMRAH = 'umrah';
    public const SERVICE_VISA = 'visa';
    public const SERVICE_CARGO = 'cargo';
    public const SERVICE_APPOINTMENT = 'appointment';
    public const SERVICE_INVESTOR = 'investor';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getTranslatedLabelAttribute(): string
    {
        $locale = app()->getLocale();
        $label = $this->label;
        
        if (is_array($label)) {
            return $label[$locale] ?? $label['en'] ?? '';
        }
        
        return is_string($label) ? $label : '';
    }

    public function getTranslatedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $title = $this->title;
        
        if (is_array($title)) {
            return $title[$locale] ?? $title['en'] ?? '';
        }
        
        return is_string($title) ? $title : '';
    }

    public function getTranslatedSubtitleAttribute(): string
    {
        $locale = app()->getLocale();
        $subtitle = $this->subtitle;
        
        if (is_array($subtitle)) {
            return $subtitle[$locale] ?? $subtitle['en'] ?? '';
        }
        
        return is_string($subtitle) ? $subtitle : '';
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $description = $this->description;
        
        if (is_array($description)) {
            return $description[$locale] ?? $description['en'] ?? null;
        }
        
        return is_string($description) ? $description : null;
    }

    public function getTranslatedButtonTextAttribute(): string
    {
        $locale = app()->getLocale();
        $buttonText = $this->button_text;
        
        if (is_array($buttonText)) {
            return $buttonText[$locale] ?? $buttonText['en'] ?? '';
        }
        
        return is_string($buttonText) ? $buttonText : '';
    }

    public function getTranslatedFeaturesAttribute(): array
    {
        $features = $this->features;
        
        if (!$features) {
            return [];
        }

        if (!is_array($features)) {
            return [];
        }

        $locale = app()->getLocale();

        return collect($features)->map(function ($feature) use ($locale) {
            if (is_array($feature)) {
                return $feature[$locale] ?? $feature['en'] ?? '';
            }
            return $feature;
        })->filter()->values()->toArray();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return Storage::url($this->image);
    }

    /**
     * Get the URL for this tab's button
     */
    public function getButtonUrlResolved(): ?string
    {
        $locale = app()->getLocale();

        // Use direct URL if set
        if ($this->button_url) {
            if (str_starts_with($this->button_url, ['http://', 'https://'])) {
                return $this->button_url;
            }
            return "/{$locale}" . ltrim($this->button_url, '/');
        }

        // Use route if set
        if ($this->route_name) {
            try {
                $params = $this->route_params ?? [];
                $params['locale'] = $locale;
                return route($this->route_name, $params);
            } catch (\Exception $e) {
                return null;
            }
        }

        // Generate URL based on service type
        return $this->generateServiceUrl($locale);
    }

    protected function generateServiceUrl(string $locale): ?string
    {
        return match ($this->tab_key) {
            self::TAB_FLIGHT => "/{$locale}/services/airticket",
            self::TAB_UMRAH => "/{$locale}/services/umrah",
            self::TAB_VISA => "/{$locale}/services/visa",
            self::TAB_CARGO => "/{$locale}/cargo",
            self::TAB_APPOINTMENT => "/{$locale}/appointment",
            self::TAB_INVESTOR => "/{$locale}/investor",
            default => null,
        };
    }

    /**
     * Get all active tabs for homepage
     */
    public static function getActiveTabs(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = 'hero_tabs_' . app()->getLocale();

        return Cache::remember($cacheKey, 3600, function () {
            return self::active()
                ->ordered()
                ->get();
        });
    }

    /**
     * Get tabs for navigation (shown in header)
     */
    public static function getNavTabs(): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = 'hero_tabs_nav_' . app()->getLocale();

        return Cache::remember($cacheKey, 3600, function () {
            return self::active()
                ->inNav()
                ->ordered()
                ->get();
        });
    }

    public static function clearCache(): void
    {
        // Clear all locale-specific caches
        foreach (['bn', 'en', 'ar'] as $locale) {
            Cache::forget("hero_tabs_{$locale}");
            Cache::forget("hero_tabs_nav_{$locale}");
        }
        // Also clear the legacy cache key (without locale)
        Cache::forget("header_nav_tabs");
        Cache::forget("services_active_tabs");
    }

    protected static function booted(): void
    {
        static::updated(fn(HeroTab $tab) => self::clearCache());
        static::deleted(fn(HeroTab $tab) => self::clearCache());
        static::created(fn(HeroTab $tab) => self::clearCache());
    }
}
