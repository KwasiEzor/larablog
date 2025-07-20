<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create popular tags
        $popularTags = [
            // Technology tags
            'Laravel',
            'PHP',
            'JavaScript',
            'React',
            'Vue.js',
            'Node.js',
            'Python',
            'Java',
            'C#',
            'Swift',
            'Mobile',
            'Web Development',
            'API',
            'Database',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'Docker',
            'Kubernetes',
            'AWS',
            'Azure',
            'Git',
            'GitHub',
            'CI/CD',
            'Testing',
            'TDD',
            'Agile',

            // Business tags
            'Startup',
            'Entrepreneurship',
            'Marketing',
            'SEO',
            'Social Media',
            'Content Marketing',
            'Email Marketing',
            'Analytics',
            'Growth Hacking',
            'Product Management',
            'Customer Success',
            'Sales',
            'Finance',
            'Investment',
            'Cryptocurrency',
            'Blockchain',
            'E-commerce',

            // Lifestyle tags
            'Productivity',
            'Time Management',
            'Mindfulness',
            'Meditation',
            'Fitness',
            'Nutrition',
            'Health',
            'Wellness',
            'Travel',
            'Photography',
            'Cooking',
            'Recipes',
            'Fashion',
            'Style',
            'Personal Development',
            'Self-Help',
            'Motivation',
            'Success',
            'Leadership',

            // Entertainment tags
            'Movies',
            'TV Shows',
            'Netflix',
            'Streaming',
            'Music',
            'Podcasts',
            'Gaming',
            'Video Games',
            'Books',
            'Reading',
            'Literature',
            'Art',
            'Design',
            'Creativity',
            'Photography',
            'Film',

            // Science tags
            'Science',
            'Technology',
            'Innovation',
            'Research',
            'Space',
            'Astronomy',
            'Biology',
            'Physics',
            'Chemistry',
            'Mathematics',
            'Engineering',
            'Medicine',
            'Health Science',
            'Environmental Science',
            'Climate Change',
            'Sustainability',

            // General tags
            'Tutorial',
            'How-to',
            'Guide',
            'Tips',
            'Tricks',
            'Best Practices',
            'Case Study',
            'Interview',
            'Review',
            'Comparison',
            'News',
            'Trends',
            'Future',
            'Innovation',
            'Education',
            'Learning',
            'Online Course',
            'Tutorial',
            'Documentation',
        ];

        foreach ($popularTags as $tagName) {
            Tag::create(['name' => $tagName]);
        }

        // Create some additional random tags using factory
        Tag::factory(20)->create();
    }
}
