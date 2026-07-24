<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['type' => 'photo', 'title' => ['en' => 'Our Office Building', 'bn' => 'আমাদের অফিস ভবন'], 'description' => ['en' => 'Modern office in Riyadh'], 'category' => 'office', 'order' => 1, 'is_featured' => true, 'status' => true],
            ['type' => 'photo', 'title' => ['en' => 'Mecca Pilgrimage Group', 'bn' => 'মক্কা তীর্থযাত্রী দল'], 'description' => ['en' => 'Happy pilgrims during Umrah'], 'category' => 'umrah', 'order' => 2, 'is_featured' => true, 'status' => true],
            ['type' => 'photo', 'title' => ['en' => 'Team Building Event', 'bn' => 'টিম বিল্ডিং ইভেন্ট'], 'description' => ['en' => 'Our annual team building'], 'category' => 'events', 'order' => 3, 'is_featured' => false, 'status' => true],
            ['type' => 'photo', 'title' => ['en' => 'Award Ceremony', 'bn' => 'পুরস্কার অনুষ্ঠান'], 'description' => ['en' => 'Travel Agency Award'], 'category' => 'awards', 'order' => 4, 'is_featured' => true, 'status' => true],
            ['type' => 'photo', 'title' => ['en' => 'Airport Lounge', 'bn' => 'বিমানবন্দর লাউঞ্জ'], 'description' => ['en' => 'VIP lounge for premium customers'], 'category' => 'services', 'order' => 5, 'is_featured' => false, 'status' => true],
            ['type' => 'video', 'title' => ['en' => 'Company Introduction', 'bn' => 'কোম্পানি পরিচিতি'], 'video_url' => 'https://www.youtube.com/watch?v=example', 'category' => 'promo', 'order' => 6, 'is_featured' => true, 'status' => true],
        ];
        foreach ($items as $itemData) {
            GalleryItem::updateOrCreate(['title' => $itemData['title'], 'type' => $itemData['type']], $itemData);
        }
        $this->command->info('GallerySeeder: Created gallery items!');
    }
}
