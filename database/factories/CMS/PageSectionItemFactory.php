<?php

namespace Database\Factories\CMS;

use App\Models\CMS\PageSection;
use App\Models\CMS\PageSectionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageSectionItemFactory extends Factory
{
    protected $model = PageSectionItem::class;

    public function definition(): array
    {
        return [
            'page_section_id' => PageSection::factory(),
            'title' => [
                'en' => $this->faker->words(3, true),
                'bn' => $this->faker->words(3, true),
                'ar' => $this->faker->words(3, true),
            ],
            'subtitle' => [
                'en' => $this->faker->sentence(),
            ],
            'description' => [
                'en' => $this->faker->paragraph(),
            ],
            'icon' => 'cube',
            'image' => null,
            'image_alt' => null,
            'link_text' => [
                'en' => 'Learn More',
            ],
            'link_url' => '/about',
            'link_target' => '_self',
            'extra' => null,
            'order' => 0,
            'status' => true,
        ];
    }
}
