<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users, categories, and tags
        $users = User::all();
        $categories = Category::all();
        $tags = Tag::all();

        if ($users->isEmpty() || $categories->isEmpty() || $tags->isEmpty()) {
            $this->command->error('Please run UserSeeder, CategorySeeder, and TagSeeder first!');
            return;
        }

        // Create featured posts with specific content
        $featuredPosts = [
            [
                'title' => 'Getting Started with Laravel: A Complete Beginner\'s Guide',
                'excerpt' => 'Learn the fundamentals of Laravel framework and build your first web application with this comprehensive guide.',
                'content' => $this->getLaravelPostContent(),
                'is_published' => true,
                'is_featured' => true,
                'category_name' => 'Programming',
                'tags' => ['Laravel', 'PHP', 'Web Development', 'Tutorial', 'Beginner'],
            ],
            [
                'title' => 'The Future of Artificial Intelligence in 2024',
                'excerpt' => 'Explore the latest AI trends, breakthroughs, and what to expect in the coming year.',
                'content' => $this->getAIPostContent(),
                'is_published' => true,
                'is_featured' => true,
                'category_name' => 'Artificial Intelligence',
                'tags' => ['Artificial Intelligence', 'Machine Learning', 'Technology', 'Future', 'Innovation'],
            ],
            [
                'title' => 'Building a Successful Startup: Lessons from the Trenches',
                'excerpt' => 'Real insights from entrepreneurs who have built and scaled successful startups.',
                'content' => $this->getStartupPostContent(),
                'is_published' => true,
                'is_featured' => true,
                'category_name' => 'Startups',
                'tags' => ['Startup', 'Entrepreneurship', 'Business', 'Success', 'Leadership'],
            ],
            [
                'title' => 'Mastering React Hooks: A Deep Dive',
                'excerpt' => 'Understanding React Hooks from basics to advanced patterns with practical examples.',
                'content' => $this->getReactPostContent(),
                'is_published' => true,
                'is_featured' => true,
                'category_name' => 'Web Development',
                'tags' => ['React', 'JavaScript', 'Web Development', 'Tutorial', 'Frontend'],
            ],
            [
                'title' => 'The Complete Guide to Digital Marketing in 2024',
                'excerpt' => 'Comprehensive strategies for digital marketing success in the current landscape.',
                'content' => $this->getMarketingPostContent(),
                'is_published' => true,
                'is_featured' => true,
                'category_name' => 'Marketing',
                'tags' => ['Marketing', 'Digital Marketing', 'SEO', 'Social Media', 'Business'],
            ],
        ];

        foreach ($featuredPosts as $postData) {
            $category = $categories->where('name', $postData['category_name'])->first();
            if (!$category) continue;

            $post = Post::create([
                'title' => $postData['title'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'meta_title' => $postData['title'],
                'meta_description' => $postData['excerpt'],
                'meta_keywords' => implode(', ', $postData['tags']),
                'is_published' => $postData['is_published'],
                'is_featured' => $postData['is_featured'],
                'user_id' => $users->random()->id,
                'category_id' => $category->id,
            ]);

            // Attach tags
            $postTags = $tags->whereIn('name', $postData['tags']);
            $post->tags()->attach($postTags->pluck('id'));
        }

        // Create regular posts using factory
        Post::factory(50)->create()->each(function ($post) use ($users, $categories, $tags) {
            // Ensure post has a valid user and category
            if (!$post->user_id) {
                $post->update(['user_id' => $users->random()->id]);
            }
            if (!$post->category_id) {
                $post->update(['category_id' => $categories->random()->id]);
            }

            // Attach random tags (2-5 tags per post)
            $randomTags = $tags->random(rand(2, 5));
            $post->tags()->attach($randomTags->pluck('id'));
        });
    }

    private function getLaravelPostContent(): string
    {
        return "
        <h2>Introduction to Laravel</h2>
        <p>Laravel is a web application framework with expressive, elegant syntax. It takes the pain out of development by easing common tasks used in many web projects, such as authentication, routing, sessions, and caching.</p>

        <h3>Why Choose Laravel?</h3>
        <ul>
            <li><strong>Elegant Syntax:</strong> Laravel provides a clean, simple syntax that makes coding enjoyable.</li>
            <li><strong>Built-in Features:</strong> Authentication, authorization, caching, and more come out of the box.</li>
            <li><strong>Active Community:</strong> Large community with extensive documentation and packages.</li>
            <li><strong>Security:</strong> Built-in security features to protect against common vulnerabilities.</li>
        </ul>

        <h3>Getting Started</h3>
        <p>To get started with Laravel, you'll need PHP 8.1+ and Composer installed on your system. Here's how to create your first Laravel project:</p>

        <pre><code>composer create-project laravel/laravel my-blog
cd my-blog
php artisan serve</code></pre>

        <h3>Key Concepts</h3>
        <p>Laravel follows the MVC (Model-View-Controller) pattern, which helps organize your code and makes it more maintainable. The framework also includes powerful features like Eloquent ORM, Blade templating, and Artisan CLI.</p>
        ";
    }

    private function getAIPostContent(): string
    {
        return "
        <h2>The AI Revolution Continues</h2>
        <p>Artificial Intelligence has transformed from a futuristic concept to an integral part of our daily lives. In 2024, we're witnessing unprecedented advancements in AI technology that are reshaping industries and creating new opportunities.</p>

        <h3>Key AI Trends in 2024</h3>
        <ul>
            <li><strong>Generative AI:</strong> Text, image, and video generation capabilities are becoming more sophisticated.</li>
            <li><strong>AI in Healthcare:</strong> Diagnostic tools and personalized medicine are improving patient outcomes.</li>
            <li><strong>Autonomous Systems:</strong> Self-driving cars and drones are becoming more reliable.</li>
            <li><strong>AI Ethics:</strong> Focus on responsible AI development and deployment.</li>
        </ul>

        <h3>Impact on Various Industries</h3>
        <p>AI is revolutionizing multiple sectors including healthcare, finance, education, and entertainment. Companies are leveraging AI to improve efficiency, reduce costs, and create better user experiences.</p>

        <h3>Future Outlook</h3>
        <p>As AI technology continues to evolve, we can expect even more groundbreaking developments. The key is to embrace these changes while ensuring ethical and responsible use of AI technologies.</p>
        ";
    }

    private function getStartupPostContent(): string
    {
        return "
        <h2>Building Your Startup Dream</h2>
        <p>Starting a business is one of the most challenging yet rewarding journeys you can undertake. Success requires more than just a great idea – it demands strategic planning, execution, and resilience.</p>

        <h3>Essential Steps for Startup Success</h3>
        <ul>
            <li><strong>Market Research:</strong> Understand your target audience and competition thoroughly.</li>
            <li><strong>MVP Development:</strong> Build a minimum viable product to test your concept.</li>
            <li><strong>Customer Validation:</strong> Get feedback from real users early and often.</li>
            <li><strong>Team Building:</strong> Surround yourself with talented people who share your vision.</li>
        </ul>

        <h3>Common Pitfalls to Avoid</h3>
        <p>Many startups fail due to common mistakes such as poor cash flow management, lack of focus, and ignoring customer feedback. Learning from others' mistakes can save you time and resources.</p>

        <h3>Scaling Your Business</h3>
        <p>Once you've validated your product-market fit, focus on scaling efficiently. This involves optimizing operations, expanding your team strategically, and maintaining quality as you grow.</p>
        ";
    }

    private function getReactPostContent(): string
    {
        return "
        <h2>Understanding React Hooks</h2>
        <p>React Hooks were introduced in React 16.8 to allow you to use state and other React features without writing a class. They provide a more direct API to the React concepts you already know.</p>

        <h3>Core Hooks</h3>
        <ul>
            <li><strong>useState:</strong> Manages local state in functional components.</li>
            <li><strong>useEffect:</strong> Handles side effects like data fetching and subscriptions.</li>
            <li><strong>useContext:</strong> Consumes React context without nesting.</li>
            <li><strong>useReducer:</strong> Manages complex state logic.</li>
        </ul>

        <h3>Custom Hooks</h3>
        <p>Custom hooks allow you to extract component logic into reusable functions. This promotes code reuse and makes your components cleaner and more maintainable.</p>

        <h3>Best Practices</h3>
        <p>When using hooks, remember to follow the rules of hooks: only call hooks at the top level and only call hooks from React functions. This ensures that hooks work correctly and predictably.</p>
        ";
    }

    private function getMarketingPostContent(): string
    {
        return "
        <h2>Digital Marketing Mastery</h2>
        <p>Digital marketing has become essential for businesses of all sizes. With the right strategies, you can reach your target audience effectively and drive meaningful results.</p>

        <h3>Key Digital Marketing Channels</h3>
        <ul>
            <li><strong>Search Engine Optimization (SEO):</strong> Improve your website's visibility in search results.</li>
            <li><strong>Social Media Marketing:</strong> Engage with your audience on popular platforms.</li>
            <li><strong>Content Marketing:</strong> Create valuable content that attracts and retains customers.</li>
            <li><strong>Email Marketing:</strong> Build relationships and drive conversions through email campaigns.</li>
        </ul>

        <h3>Data-Driven Approach</h3>
        <p>Successful digital marketing relies on data and analytics. Track your performance, analyze results, and continuously optimize your campaigns based on insights.</p>

        <h3>Future Trends</h3>
        <p>Stay ahead by embracing emerging trends like voice search optimization, AI-powered marketing tools, and personalized customer experiences.</p>
        ";
    }
}
