<?php

declare(strict_types=1);

namespace App\Services\CMS;

use App\Models\CMS\Menu;
use App\Models\CMS\Page;
use App\Models\CMS\PageSection;
use Illuminate\Support\Facades\Cache;

class CMSCache
{
    protected int $ttl;

    public function __construct()
    {
        $this->ttl = config('cms.cache_ttl', 3600);
    }

    /**
     * Clear all CMS caches.
     */
    public function clearAll(): void
    {
        $this->clearPages();
        $this->clearMenus();
        $this->clearSettings();
    }

    /**
     * Clear all page caches.
     */
    public function clearPages(): void
    {
        // Clear homepage cache
        Cache::forget('homepage_page');

        // Get all page IDs and clear their caches
        Page::query()->pluck('id')->each(function ($id) {
            foreach (['bn', 'en', 'ar'] as $locale) {
                Cache::forget("page_{$id}_sections_{$locale}");
            }
        });
    }

    /**
     * Clear all menu caches.
     */
    public function clearMenus(): void
    {
        Menu::clearCache();
    }

    /**
     * Clear settings cache.
     */
    public function clearSettings(): void
    {
        Cache::forget('cms_theme_settings');
        Cache::forget('cms_global_settings');
        Cache::forget('cms_seo_settings');
    }

    /**
     * Clear cache for a specific page.
     */
    public function clearPage(int $pageId): void
    {
        foreach (['bn', 'en', 'ar'] as $locale) {
            Cache::forget("page_{$pageId}_sections_{$locale}");
        }

        Cache::forget('homepage_page');
    }

    /**
     * Clear cache for a specific menu.
     */
    public function clearMenu(int $menuId): void
    {
        Menu::clearCache($menuId);
    }

    /**
     * Warm up CMS caches.
     */
    public function warmUp(): void
    {
        // Pre-cache homepage
        Page::getHomepage();

        // Pre-cache all menus
        foreach (Menu::LOCATIONS as $location => $label) {
            Menu::getByLocation($location);
        }
    }

    /**
     * Get or set a cached value.
     */
    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        return Cache::remember($key, $ttl ?? $this->ttl, $callback);
    }

    /**
     * Invalidate cache by tag.
     */
    public function invalidateTag(string $tag): void
    {
        // Laravel cache tags for organized invalidation
        Cache::tags(["cms_{$tag}"])->flush();
    }

    /**
     * Get cache statistics.
     */
    public function stats(): array
    {
        return [
            'page_cache_count' => Page::query()->count(),
            'menu_count' => Menu::query()->count(),
            'cache_ttl' => $this->ttl,
        ];
    }
}
