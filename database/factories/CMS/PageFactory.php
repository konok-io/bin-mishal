<?php

namespace Database\Factories\CMS;

use App\Models\CMS\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title' => [
                'en' => ucfirst($title),
                'bn' => $this->faker->words(3, true),
                'ar' => $this->faker->words(3, true),
            ],
            'slug' => [
                'en' => \Illuminate\Support\Str::slug($title),
                'bn' => \Illuminate\Support\Str::slug($this->faker->words(3, true)),
                'ar' => \Illuminate\Support\Str::slug($this->faker->words(3, true)),
            ],
            'parent_id' => null,
            'template' => 'default',
            'is_homepage' => false,
            'is_system' => false,
            'show_header' => true,
            'show_footer' => true,
            'show_breadcrumb' => true,
            'hero_type' => 'none',
            'hero_image' => null,
            'hero_title' => null,
            'hero_subtitle' => null,
            'hero_video_url' => null,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'og_image' => null,
            'canonical_url' => null,
            'noindex' => false,
            'schema_type' => null,
            'layout' => 'public',
            'custom_css' => null,
            'custom_js' => null,
            'order' => 0,
            'status' => 'published',
            'published_at' => now(),
            'scheduled_at' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function homepage(): static
    {
        return $this->state(fn($attributes) => [
            'is_homepage' => true,
            'slug' => [
                'en' => 'home',
                'bn' => 'home',
                'ar' => 'home',
            ],
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn($attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function withHero(): static
    {
        return $this->state(fn($attributes) => [
            'hero_type' => 'image',
            'hero_image' => 'hero.jpg',
            'hero_title' => ['en' => 'Welcome'],
            'hero_subtitle' => ['en' => 'Subtitle'],
        ]);
    }
}
