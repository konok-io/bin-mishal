<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'group' => $this->faker->randomElement(['app', 'navigation', 'common', 'home', 'validation']),
            'key' => $this->faker->unique()->word(),
            'value_bn' => $this->faker->sentence(),
            'value_en' => $this->faker->sentence(),
            'value_ar' => $this->faker->sentence(),
            'source' => 'code',
            'status' => 'complete',
            'last_seen_in_code_at' => now(),
        ];
    }

    public function incomplete(): static
    {
        return $this->state(fn($attributes) => [
            'value_bn' => null,
            'status' => 'missing_bn',
        ]);
    }

    public function missingBn(): static
    {
        return $this->state(fn($attributes) => [
            'value_bn' => null,
            'status' => 'missing_bn',
        ]);
    }

    public function missingEn(): static
    {
        return $this->state(fn($attributes) => [
            'value_en' => null,
            'status' => 'missing_en',
        ]);
    }

    public function missingAr(): static
    {
        return $this->state(fn($attributes) => [
            'value_ar' => null,
            'status' => 'missing_ar',
        ]);
    }
}
