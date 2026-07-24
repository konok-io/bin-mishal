<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            [
                'platform' => 'facebook',
                'name' => ['en' => 'Facebook', 'bn' => 'ফেসবুক', 'ar' => 'فيسبوك'],
                'icon' => 'fab fa-facebook-f',
                'url' => 'https://facebook.com/travelagency',
                'color' => '#1877F2',
                'order' => 1,
                'is_visible' => true,
            ],
            [
                'platform' => 'twitter',
                'name' => ['en' => 'X (Twitter)', 'bn' => 'টুইটার', 'ar' => 'تويتر'],
                'icon' => 'fab fa-x-twitter',
                'url' => 'https://twitter.com/travelagency',
                'color' => '#000000',
                'order' => 2,
                'is_visible' => true,
            ],
            [
                'platform' => 'instagram',
                'name' => ['en' => 'Instagram', 'bn' => 'ইনস্টাগ্রাম', 'ar' => 'إنستغرام'],
                'icon' => 'fab fa-instagram',
                'url' => 'https://instagram.com/travelagency',
                'color' => '#E4405F',
                'order' => 3,
                'is_visible' => true,
            ],
            [
                'platform' => 'youtube',
                'name' => ['en' => 'YouTube', 'bn' => 'ইউটিউব', 'ar' => 'يوتيوب'],
                'icon' => 'fab fa-youtube',
                'url' => 'https://youtube.com/travelagency',
                'color' => '#FF0000',
                'order' => 4,
                'is_visible' => true,
            ],
            [
                'platform' => 'linkedin',
                'name' => ['en' => 'LinkedIn', 'bn' => 'লিংকডইন', 'ar' => 'لينكد إن'],
                'icon' => 'fab fa-linkedin-in',
                'url' => 'https://linkedin.com/company/travelagency',
                'color' => '#0A66C2',
                'order' => 5,
                'is_visible' => true,
            ],
            [
                'platform' => 'whatsapp',
                'name' => ['en' => 'WhatsApp', 'bn' => 'হোয়াটসঅ্যাপ', 'ar' => 'واتساب'],
                'icon' => 'fab fa-whatsapp',
                'url' => 'https://wa.me/966501234567',
                'color' => '#25D366',
                'order' => 6,
                'is_visible' => true,
            ],
            [
                'platform' => 'tiktok',
                'name' => ['en' => 'TikTok', 'bn' => 'টিকটক', 'ar' => 'تيك توك'],
                'icon' => 'fab fa-tiktok',
                'url' => 'https://tiktok.com/@travelagency',
                'color' => '#000000',
                'order' => 7,
                'is_visible' => true,
            ],
            [
                'platform' => 'snapchat',
                'name' => ['en' => 'Snapchat', 'bn' => 'স্ন্যাপচ্যাট', 'ar' => 'سناب شات'],
                'icon' => 'fab fa-snapchat',
                'url' => 'https://snapchat.com/add/travelagency',
                'color' => '#FFFC00',
                'order' => 8,
                'is_visible' => true,
            ],
        ];

        foreach ($links as $linkData) {
            SocialLink::updateOrCreate(
                ['platform' => $linkData['platform']],
                $linkData
            );
        }

        $this->command->info('SocialLinkSeeder: Created ' . count($links) . ' social links!');
    }
}
