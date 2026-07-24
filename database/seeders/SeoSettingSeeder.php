<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'page' => 'home',
                'locale' => 'en',
                'meta_title' => 'Travel Agency - Your Gateway to the World',
                'meta_description' => "Book flights, hotels, Umrah packages, and more. Saudi Arabia's trusted travel partner for all your travel needs.",
                'meta_keywords' => 'travel, flights, hotels, Umrah, visa, Saudi Arabia',
                'og_title' => 'Travel Agency - Your Gateway to the World',
                'og_description' => 'Book flights, hotels, Umrah packages, and more.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'home',
                'locale' => 'bn',
                'meta_title' => 'ট্রাভেল এজেন্সি - বিশ্বের দরজা',
                'meta_description' => 'ফ্লাইট, হোটেল, উমরাহ প্যাকেজ বুক করুন। আপনার সব ভ্রমণ প্রয়োজনে সৌদি আরবের বিশ্বস্ত ভ্রমণ অংশীদার।',
                'meta_keywords' => 'ভ্রমণ, ফ্লাইট, হোটেল, উমরাহ, ভিসা',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'home',
                'locale' => 'ar',
                'meta_title' => 'وكالة السفر - بوابتك إلى العالم',
                'meta_description' => 'احجز رحلات الطيران والفنادق وحزم العمرة والمزيد. شريك السفر الموثوق به في المملكة العربية السعودية.',
                'meta_keywords' => 'السفر، رحلات الطيران، الفنادق، العمرة، التأشيرة',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'about',
                'locale' => 'en',
                'meta_title' => 'About Us - Our Story',
                'meta_description' => 'Learn about our travel agency's history, mission, and the dedicated team that makes your travel dreams come true.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'flight',
                'locale' => 'en',
                'meta_title' => 'Book Flight Tickets - Domestic & International',
                'meta_description' => 'Find the best deals on flight tickets. Domestic and international flights at competitive prices.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'umrah',
                'locale' => 'en',
                'meta_title' => 'Umrah Packages 2026 - Complete Guide',
                'meta_description' => 'Book your Umrah package with us. Complete Umrah services including visa, hotels, and transport.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'visa',
                'locale' => 'en',
                'meta_title' => 'Visa Services - Tourist & Business Visa',
                'meta_description' => 'Fast and reliable visa processing for all destinations. Tourist, business, and work visas available.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'cargo',
                'locale' => 'en',
                'meta_title' => 'Cargo Services - Air & Sea Freight',
                'meta_description' => 'Reliable cargo services. Air freight, sea freight, and land transport to destinations worldwide.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'contact',
                'locale' => 'en',
                'meta_title' => 'Contact Us - Get in Touch',
                'meta_description' => 'Contact our travel experts. We're here to help with all your travel needs.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page' => 'blog',
                'locale' => 'en',
                'meta_title' => 'Travel Blog - Tips & Guides',
                'meta_description' => 'Travel tips, destination guides, and expert advice from our team.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
        ];

        foreach ($settings as $settingData) {
            SeoSetting::updateOrCreate(
                [
                    'page' => $settingData['page'],
                    'locale' => $settingData['locale'],
                ],
                $settingData
            );
        }

        $this->command->info('SeoSettingSeeder: Created ' . count($settings) . ' SEO settings!');
    }
}
