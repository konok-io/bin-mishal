<?php

namespace Database\Factories\CMS;

use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'parent_id' => null,
            'title' => [
                'en' => $this->faker->words(2, true),
                'bn' => $this->faker->words(2, true),
                'ar' => $this->faker->words(2, true),
            ],
            'description' => [
                'en' => $this->faker->sentence(),
            ],
            'type' => 'custom',
            'url' => '/',
            'route_name' => null,
            'route_params' => null,
            'page_id' => null,
            'icon' => null,
            'target' => '_self',
            'css_class' => null,
            'badge_text' => null,
            'badge_color' => null,
            'is_mega' => false,
            'mega_column' => null,
            'order' => 0,
            'status' => true,
        ];
    }
}
