<?php

declare(strict_types=1);

namespace Tests\Feature\CMS;

use App\Models\CMS\Page;
use App\Models\CMS\PageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageResolutionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_homepage_resolves_correctly(): void
    {
        // Create a homepage
        $homepage = Page::factory()->create([
            'title' => ['en' => 'Home', 'bn' => 'হোম', 'ar' => 'الرئيسية'],
            'slug' => ['en' => 'home', 'bn' => 'হোম', 'ar' => 'الرئيسية'],
            'is_homepage' => true,
            'status' => 'published',
        ]);

        // Test English
        $this->get('/en')
            ->assertStatus(200);

        // Test Bengali
        $this->get('/bn')
            ->assertStatus(200);

        // Test Arabic
        $this->get('/ar')
            ->assertStatus(200);
    }

    public function test_page_resolves_by_slug_per_locale(): void
    {
        $page = Page::factory()->create([
            'title' => ['en' => 'About Us', 'bn' => 'আমাদের সম্পর্কে', 'ar' => 'من نحن'],
            'slug' => ['en' => 'about', 'bn' => 'আমাদের-সম্পর্কে', 'ar' => 'من-نحن'],
            'status' => 'published',
        ]);

        // English
        $this->get('/en/about')
            ->assertStatus(200);

        // Bengali
        $this->get('/bn/আমাদের-সম্পর্কে')
            ->assertStatus(200);

        // Arabic
        $this->get('/ar/من-نحن')
            ->assertStatus(200);
    }

    public function test_draft_page_returns_404(): void
    {
        $page = Page::factory()->create([
            'title' => ['en' => 'Draft Page'],
            'slug' => ['en' => 'draft-page'],
            'status' => 'draft',
        ]);

        $this->get('/en/draft-page')
            ->assertStatus(404);
    }

    public function test_draft_page_visible_with_preview(): void
    {
        $page = Page::factory()->create([
            'title' => ['en' => 'Draft Page'],
            'slug' => ['en' => 'draft-page'],
            'status' => 'draft',
        ]);

        $this->get('/en/draft-page?preview=1')
            ->assertStatus(200);
    }

    public function test_nested_page_resolves(): void
    {
        $parent = Page::factory()->create([
            'title' => ['en' => 'About'],
            'slug' => ['en' => 'about'],
            'status' => 'published',
        ]);

        $child = Page::factory()->create([
            'title' => ['en' => 'Our Story'],
            'slug' => ['en' => 'our-story'],
            'parent_id' => $parent->id,
            'status' => 'published',
        ]);

        $this->get('/en/about/our-story')
            ->assertStatus(200);
    }

    public function test_page_not_found_returns_404(): void
    {
        $this->get('/en/non-existent-page')
            ->assertStatus(404);
    }

    public function test_seo_redirect_works(): void
    {
        // Create old page that was moved
        $redirect = \App\Models\CMS\SeoRedirect::factory()->create([
            'old_path' => '/en/old-page',
            'new_path' => '/en/new-page',
            'type' => '301',
            'is_active' => true,
        ]);

        // Create the new page
        Page::factory()->create([
            'title' => ['en' => 'New Page'],
            'slug' => ['en' => 'new-page'],
            'status' => 'published',
        ]);

        $this->get('/en/old-page')
            ->assertStatus(301);
    }
}
