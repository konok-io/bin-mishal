<?php

namespace Database\Factories\CMS;

use App\Models\CMS\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug(2),
            'location' => $this->faker->randomElement(array_keys(Menu::LOCATIONS)),
            'description' => $this->faker->sentence(),
            'status' => true,
        ];
    }
}
