<?php

namespace Database\Seeders;

use App\Models\CMS\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['title' => ['en' => 'About Us', 'bn' => 'আমাদের সম্পর্কে'], 'slug' => ['en' => 'about'], 'template' => 'default', 'status' => 'published', 'order' => 1],
            ['title' => ['en' => 'Our Services', 'bn' => 'আমাদের সেবাসমূহ'], 'slug' => ['en' => 'services'], 'template' => 'listing', 'status' => 'published', 'order' => 2],
            ['title' => ['en' => 'Terms & Conditions', 'bn' => 'শর্তাবলী'], 'slug' => ['en' => 'terms'], 'template' => 'default', 'status' => 'published', 'order' => 10],
            ['title' => ['en' => 'Privacy Policy', 'bn' => 'গোপনীয়তা নীতি'], 'slug' => ['en' => 'privacy'], 'template' => 'default', 'status' => 'published', 'order' => 11],
        ];
        foreach ($pages as $pageData) {
            $slug = $pageData['slug']['en'];
            Page::updateOrCreate(['slug->en' => $slug], $pageData);
        }
        $this->command->info('PageSeeder: Created sample CMS pages!');
    }
}
