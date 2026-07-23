<?php

namespace Database\Factories\CMS;

use App\Models\CMS\SeoRedirect;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoRedirectFactory extends Factory
{
    protected $model = SeoRedirect::class;

    public function definition(): array
    {
        return [
            'old_path' => '/en/' . $this->faker->unique()->slug(),
            'new_path' => '/en/' . $this->faker->slug(),
            'type' => '301',
            'is_active' => true,
            'hit_count' => 0,
            'description' => $this->faker->sentence(),
            'last_hit_at' => null,
        ];
    }
}
