<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main categories
        $mainCategories = [
            [
                'name' => 'Technology',
                'description' => 'Latest technology trends, reviews, and insights',
                'is_active' => true,
                'is_featured' => true,
                'children' => [
                    ['name' => 'Programming', 'description' => 'Programming languages, frameworks, and development tips'],
                    ['name' => 'Artificial Intelligence', 'description' => 'AI, machine learning, and automation'],
                    ['name' => 'Web Development', 'description' => 'Frontend, backend, and full-stack development'],
                    ['name' => 'Mobile Development', 'description' => 'iOS, Android, and cross-platform development'],
                ]
            ],
            [
                'name' => 'Business',
                'description' => 'Business strategies, entrepreneurship, and market insights',
                'is_active' => true,
                'is_featured' => true,
                'children' => [
                    ['name' => 'Startups', 'description' => 'Startup advice, funding, and growth strategies'],
                    ['name' => 'Marketing', 'description' => 'Digital marketing, SEO, and brand building'],
                    ['name' => 'Finance', 'description' => 'Personal finance, investing, and financial planning'],
                    ['name' => 'Leadership', 'description' => 'Management, leadership, and team building'],
                ]
            ],
            [
                'name' => 'Lifestyle',
                'description' => 'Health, wellness, and personal development',
                'is_active' => true,
                'is_featured' => false,
                'children' => [
                    ['name' => 'Health & Fitness', 'description' => 'Exercise, nutrition, and wellness tips'],
                    ['name' => 'Travel', 'description' => 'Travel guides, destinations, and experiences'],
                    ['name' => 'Food & Cooking', 'description' => 'Recipes, cooking tips, and food culture'],
                    ['name' => 'Personal Development', 'description' => 'Self-improvement, productivity, and mindset'],
                ]
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Movies, music, games, and pop culture',
                'is_active' => true,
                'is_featured' => false,
                'children' => [
                    ['name' => 'Movies & TV', 'description' => 'Film reviews, TV shows, and entertainment news'],
                    ['name' => 'Music', 'description' => 'Music reviews, artist interviews, and industry news'],
                    ['name' => 'Gaming', 'description' => 'Video games, board games, and gaming culture'],
                    ['name' => 'Books', 'description' => 'Book reviews, reading lists, and literary discussions'],
                ]
            ],
            [
                'name' => 'Science',
                'description' => 'Scientific discoveries, research, and educational content',
                'is_active' => true,
                'is_featured' => false,
                'children' => [
                    ['name' => 'Space & Astronomy', 'description' => 'Space exploration, astronomy, and cosmic discoveries'],
                    ['name' => 'Biology', 'description' => 'Life sciences, genetics, and medical breakthroughs'],
                    ['name' => 'Physics', 'description' => 'Physics concepts, discoveries, and applications'],
                    ['name' => 'Chemistry', 'description' => 'Chemical processes, materials, and innovations'],
                ]
            ],
        ];

        foreach ($mainCategories as $mainCategory) {
            $children = $mainCategory['children'] ?? [];
            unset($mainCategory['children']);

            $category = Category::create($mainCategory);

            foreach ($children as $child) {
                Category::create([
                    ...$child,
                    'parent_id' => $category->id,
                    'is_active' => true,
                    'is_featured' => false,
                ]);
            }
        }

        // Create some additional categories using factory
        Category::factory(5)->create();
    }
}
