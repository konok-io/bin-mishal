<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FeatureCard;
use App\Models\FlightRoute;
use App\Models\QuickService;
use App\Models\Statistic;
use App\Models\TrustBadge;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        // Statistics
        $statistics = [
            ['key' => 'customers', 'label' => 'Happy Customers', 'label_bn' => 'সন্তুষ্ট গ্রাহক', 'label_ar' => 'العملاء السعداء', 'value' => 15000, 'icon' => 'heroicon-o-users', 'color' => '#198754'],
            ['key' => 'tickets', 'label' => 'Tickets Sold', 'label_bn' => 'বিক্রিত টিকিট', 'label_ar' => 'التذاكر المباعة', 'value' => 25000, 'suffix' => '+', 'icon' => 'heroicon-o-ticket', 'color' => '#0d6efd'],
            ['key' => 'visas', 'label' => 'Visas Processed', 'label_bn' => 'প্রক্রিয়াকৃত ভিসা', 'label_ar' => 'التأشيرات المعالجة', 'value' => 8500, 'icon' => 'heroicon-o-document-check', 'color' => '#6f42c1'],
            ['key' => 'years', 'label' => 'Years Experience', 'label_bn' => 'বছরের অভিজ্ঞতা', 'label_ar' => 'سنوات الخبرة', 'value' => 15, 'suffix' => '+', 'icon' => 'heroicon-o-star', 'color' => '#ffc107'],
        ];

        foreach ($statistics as $index => $stat) {
            Statistic::updateOrCreate(
                ['key' => $stat['key']],
                array_merge($stat, ['sort_order' => $index, 'is_active' => true])
            );
        }

        // Trust Badges
        $trustBadges = [
            ['name' => 'IATA Certified', 'name_bn' => 'IATA সার্টিফাইড', 'name_ar' => 'معتمد من IATA', 'image_url' => 'https://via.placeholder.com/100x50/198754/ffffff?text=IATA', 'link' => '#'],
            ['name' => 'Saudi Tourism', 'name_bn' => 'সৌদি পর্যটন', 'name_ar' => 'السياحة السعودية', 'image_url' => 'https://via.placeholder.com/100x50/0d6efd/ffffff?text=SAT', 'link' => '#'],
            ['name' => 'ATAB Member', 'name_bn' => 'ATAB সদস্য', 'name_ar' => 'عضو ATAB', 'image_url' => 'https://via.placeholder.com/100x50/6f42c1/ffffff?text=ATAB', 'link' => '#'],
            ['name' => 'Secure Payment', 'name_bn' => 'নিরাপদ পেমেন্ট', 'name_ar' => 'دفع آمن', 'image_url' => 'https://via.placeholder.com/100x50/ffc107/000000?text=SSL', 'link' => '#'],
        ];

        foreach ($trustBadges as $index => $badge) {
            TrustBadge::updateOrCreate(
                ['name' => $badge['name']],
                array_merge($badge, ['sort_order' => $index, 'is_active' => true])
            );
        }

        // Quick Services
        $quickServices = [
            ['title' => 'Umrah Packages', 'title_bn' => 'উমরাহ প্যাকেজ', 'title_ar' => 'باقات العمرة', 'icon' => 'bi bi-kaaba', 'link' => '/services/umrah', 'sort_order' => 1],
            ['title' => 'Visa Services', 'title_bn' => 'ভিসা সেবা', 'title_ar' => 'خدمات التأشيرة', 'icon' => 'bi bi-passport', 'link' => '/services/visa', 'sort_order' => 2],
            ['title' => 'Air Tickets', 'title_bn' => 'এয়ার টিকিট', 'title_ar' => 'تذاكر الطيران', 'icon' => 'bi bi-airplane', 'link' => '/services/airticket', 'sort_order' => 3],
            ['title' => 'Hotel Booking', 'title_bn' => 'হোটেল বুকিং', 'title_ar' => 'حجز الفنادق', 'icon' => 'bi bi-building', 'link' => '/services/hotel', 'sort_order' => 4],
            ['title' => 'Cargo Service', 'title_bn' => 'কার্গো সেবা', 'title_ar' => 'خدمات الشحن', 'icon' => 'bi bi-truck', 'link' => '/cargo', 'sort_order' => 5],
            ['title' => 'Investor Services', 'title_bn' => 'বিনিয়োগকারী সেবা', 'title_ar' => 'خدمات المستثمرين', 'icon' => 'bi bi-briefcase', 'link' => '/investor', 'sort_order' => 6],
        ];

        foreach ($quickServices as $service) {
            QuickService::updateOrCreate(
                ['title' => $service['title']],
                array_merge($service, ['is_active' => true])
            );
        }

        // Feature Cards
        $featureCards = [
            ['title' => '24/7 Support', 'title_bn' => '২৪/৭ সহায়তা', 'title_ar' => 'الدعم على مدار الساعة', 'icon' => 'bi bi-headset', 'number' => 24, 'number_suffix' => '/7', 'number_suffix_bn' => '/৭', 'number_suffix_ar' => '/٧', 'description' => 'Always available to help you', 'description_bn' => 'সবসময় আপনাকে সাহায্য করতে প্রস্তুত', 'description_ar' => 'متاح دائمًا لمساعدتك', 'color' => '#198754', 'sort_order' => 1],
            ['title' => 'Best Prices', 'title_bn' => 'সেরা দাম', 'title_ar' => 'أفضل الأسعار', 'icon' => 'bi bi-tag', 'number' => 100, 'number_suffix' => '%', 'number_suffix_bn' => '%', 'number_suffix_ar' => '%', 'description' => 'Competitive pricing guaranteed', 'description_bn' => 'প্রতিযোগিতামূলক মূল্য নিশ্চিত', 'description_ar' => 'أسعار تنافسية مضمونة', 'color' => '#0d6efd', 'sort_order' => 2],
            ['title' => 'Easy Booking', 'title_bn' => 'সহজ বুকিং', 'title_ar' => 'الحجز السهل', 'icon' => 'bi bi-calendar-check', 'number' => 3, 'number_suffix' => ' Steps', 'number_suffix_bn' => ' ধাপ', 'number_suffix_ar' => ' خطوات', 'description' => 'Book in just 3 simple steps', 'description_bn' => 'মাত্র ৩টি সহজ ধাপে বুক করুন', 'description_ar' => 'احجز في 3 خطوات بسيطة فقط', 'color' => '#6f42c1', 'sort_order' => 3],
            ['title' => 'Trusted Agency', 'title_bn' => 'বিশ্বস্ত এজেন্সি', 'title_ar' => 'وكالة موثوقة', 'icon' => 'bi bi-shield-check', 'number' => 15, 'number_suffix' => '+ Years', 'number_suffix_bn' => '+ বছর', 'number_suffix_ar' => '+ سنوات', 'description' => 'Years of trusted service', 'description_bn' => 'বছরের বিশ্বস্ত সেবা', 'description_ar' => 'سنوات من الخدمة الموثوقة', 'color' => '#ffc107', 'sort_order' => 4],
        ];

        foreach ($featureCards as $card) {
            FeatureCard::updateOrCreate(
                ['title' => $card['title']],
                array_merge($card, ['is_active' => true])
            );
        }

        // Flight Routes
        $flightRoutes = [
            ['from_city' => 'Riyadh', 'from_city_bn' => 'রিয়াদ', 'from_city_ar' => 'الرياض', 'from_country' => 'SA', 'to_city' => 'Dhaka', 'to_city_bn' => 'ঢাকা', 'to_city_ar' => 'دكا', 'to_country' => 'BD', 'price' => 850, 'currency' => 'SAR', 'airline' => 'Saudi Arabian Airlines', 'is_featured' => true],
            ['from_city' => 'Jeddah', 'from_city_bn' => 'জেদ্দা', 'from_city_ar' => 'جدة', 'from_country' => 'SA', 'to_city' => 'Chittagong', 'to_city_bn' => 'চট্টগ্রাম', 'to_city_ar' => 'تشيتاغونغ', 'to_country' => 'BD', 'price' => 920, 'currency' => 'SAR', 'airline' => 'Biman Bangladesh', 'is_featured' => true],
            ['from_city' => 'Dammam', 'from_city_bn' => 'দাম্মাম', 'from_city_ar' => 'الدمام', 'from_country' => 'SA', 'to_city' => 'Sylhet', 'to_city_bn' => 'সিলেট', 'to_city_ar' => 'سيلهيت', 'to_country' => 'BD', 'price' => 780, 'currency' => 'SAR', 'airline' => 'US-Bangla Airlines', 'is_featured' => false],
            ['from_city' => 'Medina', 'from_city_bn' => 'মদিনা', 'from_city_ar' => 'المدينة', 'from_country' => 'SA', 'to_city' => 'Dhaka', 'to_city_bn' => 'ঢাকা', 'to_city_ar' => 'دكا', 'to_country' => 'BD', 'price' => 950, 'currency' => 'SAR', 'airline' => 'Saudi Arabian Airlines', 'is_featured' => false],
        ];

        foreach ($flightRoutes as $index => $route) {
            FlightRoute::updateOrCreate(
                ['from_city' => $route['from_city'], 'to_city' => $route['to_city']],
                array_merge($route, ['sort_order' => $index, 'is_active' => true])
            );
        }

        $this->command->info('Homepage data seeded successfully!');
    }
}
