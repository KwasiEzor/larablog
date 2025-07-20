<?php

namespace Database\Factories;

use App\Services\FakeImageService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(),
            'image' => FakeImageService::generateRealImageUrl(),
            'is_active' => $this->faker->boolean(80),
            'is_featured' => $this->faker->boolean(20),
            'parent_id' => null,
        ];
    }
}
