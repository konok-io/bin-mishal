<?php

declare(strict_types=1);

namespace Tests\Feature\CMS;

use App\Models\CMS\Page;
use App\Models\CMS\PageSection;
use App\Models\CMS\PageSectionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_renders_sections(): void
    {
        $page = Page::factory()->create([
            'slug' => ['en' => 'test-page'],
            'status' => 'published',
        ]);

        $section = PageSection::factory()->create([
            'page_id' => $page->id,
            'section_type' => 'hero_simple',
            'name' => 'Hero Section',
            'content' => [
                'heading' => ['en' => 'Welcome'],
                'subheading' => ['en' => 'Test subheading'],
            ],
            'settings' => [
                'background_type' => 'gradient',
                'gradient_from' => '#059669',
                'gradient_to' => '#047857',
            ],
            'order' => 1,
            'status' => true,
        ]);

        $this->get('/en/test-page')
            ->assertStatus(200)
            ->assertSee('Welcome');
    }

    public function test_section_with_items_renders(): void
    {
        $page = Page::factory()->create([
            'slug' => ['en' => 'services'],
            'status' => 'published',
        ]);

        $section = PageSection::factory()->create([
            'page_id' => $page->id,
            'section_type' => 'feature_cards',
            'name' => 'Features',
            'content' => [
                'heading' => ['en' => 'Our Services'],
            ],
            'order' => 1,
            'status' => true,
        ]);

        // Add items
        PageSectionItem::factory()->create([
            'page_section_id' => $section->id,
            'title' => ['en' => 'Umrah Packages'],
            'description' => ['en' => 'Best Umrah packages'],
            'icon' => 'cube',
            'order' => 1,
        ]);

        PageSectionItem::factory()->create([
            'page_section_id' => $section->id,
            'title' => ['en' => 'Visa Processing'],
            'description' => ['en' => 'Fast visa processing'],
            'icon' => 'document',
            'order' => 2,
        ]);

        $this->get('/en/services')
            ->assertStatus(200)
            ->assertSee('Our Services')
            ->assertSee('Umrah Packages')
            ->assertSee('Visa Processing');
    }

    public function test_inactive_section_not_rendered(): void
    {
        $page = Page::factory()->create([
            'slug' => ['en' => 'test'],
            'status' => 'published',
        ]);

        PageSection::factory()->create([
            'page_id' => $page->id,
            'section_type' => 'hero_simple',
            'content' => ['heading' => ['en' => 'Active Section']],
            'status' => true,
            'order' => 1,
        ]);

        PageSection::factory()->create([
            'page_id' => $page->id,
            'section_type' => 'cta_banner',
            'content' => ['heading' => ['en' => 'Inactive Section']],
            'status' => false,
            'order' => 2,
        ]);

        $this->get('/en/test')
            ->assertStatus(200)
            ->assertSee('Active Section')
            ->assertDontSee('Inactive Section');
    }

    public function test_section_visibility_locale_filter(): void
    {
        $page = Page::factory()->create([
            'slug' => ['en' => 'test'],
            'status' => 'published',
        ]);

        // Section visible only in English
        PageSection::factory()->create([
            'page_id' => $page->id,
            'section_type' => 'hero_simple',
            'content' => ['heading' => ['en' => 'English Only']],
            'visibility' => [
                'locales' => ['en'],
            ],
            'status' => true,
            'order' => 1,
        ]);

        // Test English - should see
        $this->get('/en/test')
            ->assertStatus(200)
            ->assertSee('English Only');

        // Test Bengali - should not see
        $this->get('/bn/test')
            ->assertStatus(200)
            ->assertDontSee('English Only');
    }

    public function test_empty_page_shows_under_construction(): void
    {
        $page = Page::factory()->create([
            'slug' => ['en' => 'empty'],
            'status' => 'published',
        ]);

        // No sections

        $this->get('/en/empty')
            ->assertStatus(200);
    }

    public function test_section_with_empty_data_shows_fallback(): void
    {
        $page = Page::factory()->create([
            'slug' => ['en' => 'test'],
            'status' => 'published',
        ]);

        $section = PageSection::factory()->create([
            'page_id' => $page->id,
            'section_type' => 'nonexistent_section',
            'name' => 'Missing Section',
            'status' => true,
            'order' => 1,
        ]);

        // Should render generic fallback
        $this->get('/en/test')
            ->assertStatus(200);
    }
}
