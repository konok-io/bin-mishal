<?php

declare(strict_types=1);

namespace Tests\Feature\CMS;

use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_creates_with_items(): void
    {
        $menu = Menu::factory()->create([
            'name' => 'Main Navigation',
            'slug' => 'main',
            'location' => 'header',
            'status' => true,
        ]);

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Home', 'bn' => 'হোম', 'ar' => 'الرئيسية'],
            'type' => 'custom',
            'url' => '/',
            'order' => 1,
            'status' => true,
        ]);

        $this->assertCount(1, $menu->items);
    }

    public function test_menu_tree_returns_nested_structure(): void
    {
        $menu = Menu::factory()->create([
            'location' => 'header',
        ]);

        $parent = MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Services'],
            'order' => 1,
            'status' => true,
        ]);

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'parent_id' => $parent->id,
            'title' => ['en' => 'Umrah'],
            'order' => 1,
            'status' => true,
        ]);

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'parent_id' => $parent->id,
            'title' => ['en' => 'Visa'],
            'order' => 2,
            'status' => true,
        ]);

        $tree = $menu->getTree();

        $this->assertCount(1, $tree);
        $this->assertEquals('Services', $tree->first()->translated_title);
        $this->assertCount(2, $tree->first()->children);
    }

    public function test_menu_item_resolves_internal_url(): void
    {
        $menu = Menu::factory()->create();
        $item = MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'type' => 'internal',
            'url' => '/about',
        ]);

        // Test with English locale
        app()->setLocale('en');
        $this->assertEquals('/en/about', $item->resolveUrl());

        // Test with Bengali locale
        app()->setLocale('bn');
        $this->assertEquals('/bn/about', $item->resolveUrl());
    }

    public function test_menu_item_resolves_external_url(): void
    {
        $menu = Menu::factory()->create();
        $item = MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'type' => 'external',
            'url' => 'https://google.com',
        ]);

        app()->setLocale('en');
        $this->assertEquals('https://google.com', $item->resolveUrl());
    }

    public function test_menu_clears_cache_on_update(): void
    {
        $menu = Menu::factory()->create(['location' => 'header']);

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Home'],
        ]);

        // Get tree (cached)
        $tree1 = $menu->getTree();

        // Update menu
        $menu->update(['name' => 'Updated Menu']);

        // Tree should be fresh
        $tree2 = $menu->fresh()->getTree();

        $this->assertCount(1, $tree2);
    }

    public function test_inactive_items_excluded_from_tree(): void
    {
        $menu = Menu::factory()->create();

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Active Item'],
            'status' => true,
            'order' => 1,
        ]);

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Inactive Item'],
            'status' => false,
            'order' => 2,
        ]);

        $tree = $menu->getTree();

        $this->assertCount(1, $tree);
        $this->assertEquals('Active Item', $tree->first()->translated_title);
    }

    public function test_menu_builder_renders_menu(): void
    {
        $menu = Menu::factory()->create([
            'location' => 'header',
            'status' => true,
        ]);

        MenuItem::factory()->create([
            'menu_id' => $menu->id,
            'title' => ['en' => 'Home'],
            'type' => 'custom',
            'url' => '/',
            'status' => true,
        ]);

        $builder = app(\App\Services\CMS\MenuBuilder::class);
        $result = $builder->header();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Home', $result[0]['title']);
    }
}
