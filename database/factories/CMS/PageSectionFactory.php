<?php

namespace Database\Factories\CMS;

use App\Models\CMS\Page;
use App\Models\CMS\PageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageSectionFactory extends Factory
{
    protected $model = PageSection::class;

    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'section_type' => $this->faker->randomElement([
                'hero_simple',
                'cta_banner',
                'feature_cards',
                'stats_counter',
                'service_icons',
            ]),
            'name' => $this->faker->words(2, true),
            'content' => [
                'heading' => ['en' => $this->faker->sentence(3)],
                'subheading' => ['en' => $this->faker->sentence()],
            ],
            'settings' => [
                'background' => 'none',
                'padding_top' => 'default',
                'padding_bottom' => 'default',
                'container_width' => 'contained',
            ],
            'data_source' => null,
            'visibility' => null,
            'order' => 0,
            'status' => true,
        ];
    }
}
