<?php

declare(strict_types=1);

namespace Tests\Unit\CMS;

use App\Models\CMS\Menu;
use App\Models\CMS\Page;
use App\Services\CMS\CMSCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CMSCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_clear_pages_clears_cache(): void
    {
        $page = Page::factory()->create();

        // Create some cache entries
        Cache::put("page_{$page->id}_sections_en", ['test']);
        Cache::put("page_{$page->id}_sections_bn", ['test']);
        Cache::put("homepage_page", $page);

        $service = new CMSCache();
        $service->clearPages();

        $this->assertNull(Cache::get("page_{$page->id}_sections_en"));
        $this->assertNull(Cache::get("page_{$page->id}_sections_bn"));
        $this->assertNull(Cache::get("homepage_page"));
    }

    public function test_clear_menu_clears_cache(): void
    {
        $menu = Menu::factory()->create();

        Cache::put("menu_tree_{$menu->id}_en", []);
        Cache::put("menu_location_header_en", $menu);

        $service = new CMSCache();
        $service->clearMenu($menu->id);

        $this->assertNull(Cache::get("menu_tree_{$menu->id}_en"));
        $this->assertNull(Cache::get("menu_location_header_en"));
    }

    public function test_clear_all_clears_everything(): void
    {
        Cache::put('cms_theme_settings', []);
        Cache::put('cms_global_settings', []);
        Cache::put('cms_seo_settings', []);

        $service = new CMSCache();
        $service->clearAll();

        $this->assertNull(Cache::get('cms_theme_settings'));
        $this->assertNull(Cache::get('cms_global_settings'));
        $this->assertNull(Cache::get('cms_seo_settings'));
    }

    public function test_warm_up_caches_homepage_and_menus(): void
    {
        $homepage = Page::factory()->create([
            'is_homepage' => true,
        ]);

        Menu::factory()->create(['location' => 'header']);
        Menu::factory()->create(['location' => 'footer_col1']);

        $service = new CMSCache();
        $service->warmUp();

        $this->assertNotNull(Cache::get('homepage_page'));
        $this->assertNotNull(Cache::get('menu_location_header_en'));
        $this->assertNotNull(Cache::get('menu_location_footer_col1_en'));
    }
}
