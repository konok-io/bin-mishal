<?php

namespace Database\Seeders;

use App\Models\PostComment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::where('is_published', true)->take(3)->get();
        $user = User::first();

        if ($posts->isEmpty()) {
            $this->command->info('CommentSeeder: No published posts found. Run BlogPostSeeder first!');
            return;
        }

        $comments = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed@email.com',
                'comment' => 'Great article! Very informative and helpful for planning my trip.',
                'is_approved' => true,
            ],
            [
                'name' => 'Fatima Ali',
                'email' => 'fatima@email.com',
                'comment' => 'Thank you for sharing these tips. I found them very useful!',
                'is_approved' => true,
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@email.com',
                'comment' => 'Could you provide more details about the budget options?',
                'is_approved' => false,
            ],
            [
                'name' => 'Sara Khan',
                'email' => 'sara@email.com',
                'comment' => 'Excellent guide! Saved me so much time in planning.',
                'is_approved' => true,
            ],
            [
                'name' => 'Mohammed Noor',
                'email' => 'mohammed@email.com',
                'comment' => 'I have a question about the best season to visit. Any recommendations?',
                'is_approved' => true,
            ],
        ];

        $created = 0;
        foreach ($comments as $commentData) {
            $post = $posts->random();
            $commentData['post_id'] = $post->id;
            $commentData['user_id'] = $user ? $user->id : null;

            PostComment::updateOrCreate(
                [
                    'post_id' => $commentData['post_id'],
                    'email' => $commentData['email'],
                    'comment' => $commentData['comment'],
                ],
                $commentData
            );
            $created++;
        }

        $this->command->info('CommentSeeder: Created ' . $created . ' sample comments!');
    }
}
