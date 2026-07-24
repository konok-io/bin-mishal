<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first();
        $category = PostCategory::first();
        $posts = [
            ['title' => 'Top 10 Travel Destinations for 2026', 'title_bn' => '২০২৬ সালের সেরা ১০টি ভ্রমণ গন্তব্য', 'slug' => 'top-10-travel-destinations-2026', 'excerpt' => 'Discover the most breathtaking places...', 'content' => '<p>As we enter 2026...</p>', 'is_featured' => true, 'is_published' => true, 'published_at' => now()->subDays(5), 'view_count' => 150],
            ['title' => 'Umrah Guide: Everything You Need to Know', 'title_bn' => 'উমরাহ গাইড', 'slug' => 'umrah-guide-complete', 'excerpt' => 'A comprehensive guide...', 'content' => '<p>Umrah is a sacred pilgrimage...</p>', 'is_featured' => true, 'is_published' => true, 'published_at' => now()->subDays(10), 'view_count' => 320],
            ['title' => 'Business Travel Tips', 'title_bn' => 'ব্যবসায়িক ভ্রমণ টিপস', 'slug' => 'business-travel-tips', 'excerpt' => 'Maximize your productivity...', 'content' => '<p>Business travel can be challenging...</p>', 'is_featured' => false, 'is_published' => true, 'published_at' => now()->subDays(15), 'view_count' => 89],
            ['title' => 'How to Get Cheap Flight Tickets', 'title_bn' => 'সস্তা ফ্লাইট টিকিট', 'slug' => 'cheap-flight-tickets-guide', 'excerpt' => 'Expert tips on finding the best flight deals...', 'content' => '<p>Everyone loves a good deal...</p>', 'is_featured' => false, 'is_published' => true, 'published_at' => now()->subDays(20), 'view_count' => 245],
            ['title' => 'Visa Application Tips', 'title_bn' => 'ভিসা আবেদন টিপস', 'slug' => 'visa-application-tips', 'excerpt' => 'Avoid common mistakes...', 'content' => '<p>Visa applications can be tricky...</p>', 'is_featured' => false, 'is_published' => true, 'published_at' => now()->subDays(25), 'view_count' => 178],
        ];
        foreach ($posts as $postData) {
            Post::updateOrCreate(['slug' => $postData['slug']], array_merge($postData, ['author_id' => $author ? $author->id : 1, 'category_id' => $category ? $category->id : null, 'reading_time' => rand(3, 10)]));
        }
        $this->command->info('BlogPostSeeder: Created 5 sample blog posts!');
    }
}
