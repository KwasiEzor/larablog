<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing posts and users
        $posts = Post::published()->get();
        $users = User::all();

        if ($posts->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please run PostSeeder and UserSeeder first!');
            return;
        }

        // Create some realistic comments for featured posts
        $realisticComments = [
            [
                'post_title' => 'Getting Started with Laravel: A Complete Beginner\'s Guide',
                'comments' => [
                    [
                        'content' => 'This is exactly what I was looking for! The step-by-step guide is very clear and easy to follow. Thanks for sharing this comprehensive tutorial.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'Great article! I\'ve been wanting to learn Laravel and this guide makes it so much easier. The code examples are really helpful.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'I\'m a beginner and this tutorial helped me understand the basics. Looking forward to more Laravel content!',
                        'is_approved' => true,
                    ],
                ]
            ],
            [
                'post_title' => 'The Future of Artificial Intelligence in 2024',
                'comments' => [
                    [
                        'content' => 'Fascinating insights into AI trends! The healthcare applications are particularly interesting. Can\'t wait to see how this technology evolves.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'AI ethics is such an important topic that often gets overlooked. Thanks for highlighting this crucial aspect.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'The autonomous systems section was eye-opening. Self-driving technology has come so far in recent years.',
                        'is_approved' => true,
                    ],
                ]
            ],
            [
                'post_title' => 'Building a Successful Startup: Lessons from the Trenches',
                'comments' => [
                    [
                        'content' => 'As someone who\'s currently building a startup, this article hits home. The MVP development advice is spot on.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'Great insights! The section on common pitfalls really resonated with my experience. Wish I had read this earlier.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'The team building advice is crucial. Having the right people around you makes all the difference.',
                        'is_approved' => true,
                    ],
                ]
            ],
            [
                'post_title' => 'Mastering React Hooks: A Deep Dive',
                'comments' => [
                    [
                        'content' => 'Excellent explanation of React Hooks! The examples are clear and the best practices section is very helpful.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'I\'ve been using hooks for a while but learned some new patterns from this article. Great work!',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'The custom hooks section was particularly useful. It\'s amazing how much code you can reuse with custom hooks.',
                        'is_approved' => true,
                    ],
                ]
            ],
            [
                'post_title' => 'The Complete Guide to Digital Marketing in 2024',
                'comments' => [
                    [
                        'content' => 'Comprehensive guide! The data-driven approach section is especially relevant in today\'s marketing landscape.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'Great overview of digital marketing channels. The future trends section gives good insights into what\'s coming next.',
                        'is_approved' => true,
                    ],
                    [
                        'content' => 'As a small business owner, this guide is invaluable. The practical tips are easy to implement.',
                        'is_approved' => true,
                    ],
                ]
            ],
        ];

        foreach ($realisticComments as $postComments) {
            $post = $posts->where('title', $postComments['post_title'])->first();
            if (!$post) continue;

            foreach ($postComments['comments'] as $commentData) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'content' => $commentData['content'],
                    'is_approved' => $commentData['is_approved'],
                ]);
            }
        }

        // Create additional comments using factory
        Comment::factory(100)->create()->each(function ($comment) use ($posts, $users) {
            // Ensure comment has a valid post and user
            if (!$comment->post_id) {
                $comment->update(['post_id' => $posts->random()->id]);
            }
            if (!$comment->user_id) {
                $comment->update(['user_id' => $users->random()->id]);
            }
        });
    }
}
