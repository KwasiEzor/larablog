<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Services\FakeImageService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(3, true),
            'image' => FakeImageService::generateRealImageUrl(),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
            'meta_keywords' => $this->faker->words(3, true),
            'is_published' => $this->faker->boolean(80),
            'is_featured' => $this->faker->boolean(20),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
