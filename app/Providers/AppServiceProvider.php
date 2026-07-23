<?php

namespace App\Providers;

use App\Models\CMS\Menu;
use App\Models\CMS\Page;
use App\Services\CMS\CMSCache;
use App\Services\CMS\MenuBuilder;
use App\Services\CMS\SectionDataResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SectionDataResolver::class);
        $this->app->singleton(MenuBuilder::class);
        $this->app->singleton(CMSCache::class);
    }

    public function boot(): void
    {
        Page::updated(fn(Page $page) => $this->clearCmsCache($page));
        Page::deleted(fn(Page $page) => $this->clearCmsCache($page));

        Menu::updated(fn(Menu $menu) => Menu::clearCache($menu->id));
        Menu::deleted(fn(Menu $menu) => Menu::clearCache($menu->id));
    }

    protected function clearCmsCache(Page $page): void
    {
        Menu::clearCache();
        Page::clearCache($page->id);
    }
}
