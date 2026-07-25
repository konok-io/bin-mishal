<?php

namespace Database\Seeders;

use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Header Menu
        $headerMenu = Menu::updateOrCreate(
            ['slug' => 'header'],
            ['name' => 'Header Navigation', 'location' => 'header', 'status' => true]
        );

        $menuItems = [
            ['title' => ['en' => 'Home', 'bn' => 'হোম', 'ar' => 'الرئيسية'], 'url' => '/', 'icon' => 'fas fa-home', 'order' => 1, 'status' => 1],
            ['title' => ['en' => 'About Us', 'bn' => 'আমাদের সম্পর্কে', 'ar' => 'من نحن'], 'url' => '/about', 'order' => 2, 'status' => 1],
            ['title' => ['en' => 'Services', 'bn' => 'সেবাসমূহ', 'ar' => 'الخدمات'], 'url' => '/services', 'icon' => 'fas fa-cogs', 'order' => 3, 'status' => 1],
            ['title' => ['en' => 'Cargo', 'bn' => 'কার্গো', 'ar' => 'الشحن'], 'url' => '/cargo', 'icon' => 'fas fa-truck', 'order' => 4, 'status' => 1],
            ['title' => ['en' => 'Careers', 'bn' => 'ক্যারিয়ার', 'ar' => 'وظائف'], 'url' => '/careers', 'icon' => 'fas fa-briefcase', 'order' => 5, 'status' => 1],
            ['title' => ['en' => 'Blog', 'bn' => 'ব্লগ', 'ar' => 'مدونة'], 'url' => '/blog', 'icon' => 'fas fa-blog', 'order' => 6, 'status' => 1],
            ['title' => ['en' => 'Contact', 'bn' => 'যোগাযোগ', 'ar' => 'اتصل بنا'], 'url' => '/contact', 'icon' => 'fas fa-envelope', 'order' => 7, 'status' => 1],
        ];

        foreach ($menuItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $headerMenu->id, 'url' => $item['url']],
                array_merge($item, ['menu_id' => $headerMenu->id])
            );
        }

        // Services Dropdown
        $servicesParent = MenuItem::where('menu_id', $headerMenu->id)->where('url', '/services')->first();
        if ($servicesParent) {
            $services = [
                ['title' => ['en' => 'Flight Booking', 'bn' => 'ফ্লাইট', 'ar' => 'طيران'], 'url' => '/services/flight', 'icon' => 'fas fa-plane', 'order' => 1],
                ['title' => ['en' => 'Umrah Packages', 'bn' => 'উমরাহ', 'ar' => 'عمرة'], 'url' => '/services/umrah', 'icon' => 'fas fa-kaaba', 'order' => 2],
                ['title' => ['en' => 'Visa Services', 'bn' => 'ভিসা', 'ar' => 'تأشيرة'], 'url' => '/services/visa', 'icon' => 'fas fa-passport', 'order' => 3],
                ['title' => ['en' => 'Cargo Service', 'bn' => 'কার্গো', 'ar' => 'شحن'], 'url' => '/cargo', 'icon' => 'fas fa-box', 'order' => 4],
                ['title' => ['en' => 'Appointment', 'bn' => 'অ্যাপয়েন্টমেন্ট', 'ar' => 'موعد'], 'url' => '/appointment', 'icon' => 'fas fa-calendar-check', 'order' => 5],
                ['title' => ['en' => 'Investor Services', 'bn' => 'বিনিয়োগ', 'ar' => 'مستثمر'], 'url' => '/investor', 'icon' => 'fas fa-chart-line', 'order' => 6],
            ];
            foreach ($services as $s) {
                MenuItem::updateOrCreate(
                    ['menu_id' => $headerMenu->id, 'parent_id' => $servicesParent->id, 'url' => $s['url']],
                    array_merge($s, ['menu_id' => $headerMenu->id, 'parent_id' => $servicesParent->id])
                );
            }
        }

        // Footer Services
        $footerServices = Menu::updateOrCreate(['slug' => 'footer-services'], ['name' => 'Services', 'location' => 'footer_col1', 'status' => 1]);
        $services = [
            ['title' => ['en' => 'Flight Booking', 'bn' => 'ফ্লাইট', 'ar' => 'طيران'], 'url' => '/services/flight'],
            ['title' => ['en' => 'Umrah Packages', 'bn' => 'উমরাহ', 'ar' => 'عمرة'], 'url' => '/services/umrah'],
            ['title' => ['en' => 'Visa Services', 'bn' => 'ভিসা', 'ar' => 'تأشيرة'], 'url' => '/services/visa'],
            ['title' => ['en' => 'Cargo', 'bn' => 'কার্গো', 'ar' => 'شحن'], 'url' => '/cargo'],
        ];
        foreach ($services as $i => $s) {
            MenuItem::updateOrCreate(['menu_id' => $footerServices->id, 'url' => $s['url']], array_merge($s, ['menu_id' => $footerServices->id, 'order' => $i + 1, 'status' => 1]));
        }

        // Footer Quick Links
        $footerQuick = Menu::updateOrCreate(['slug' => 'footer-quick'], ['name' => 'Quick Links', 'location' => 'footer_col2', 'status' => 1]);
        $quick = [
            ['title' => ['en' => 'About Us', 'bn' => 'আমাদের', 'ar' => 'من نحن'], 'url' => '/about'],
            ['title' => ['en' => 'Contact Us', 'bn' => 'যোগাযোগ', 'ar' => 'اتصل بنا'], 'url' => '/contact'],
            ['title' => ['en' => 'Careers', 'bn' => 'ক্যারিয়ার', 'ar' => 'وظائف'], 'url' => '/careers'],
            ['title' => ['en' => 'Blog', 'bn' => 'ব্লগ', 'ar' => 'مدونة'], 'url' => '/blog'],
        ];
        foreach ($quick as $i => $q) {
            MenuItem::updateOrCreate(['menu_id' => $footerQuick->id, 'url' => $q['url']], array_merge($q, ['menu_id' => $footerQuick->id, 'order' => $i + 1, 'status' => 1]));
        }

        // Footer Legal
        $footerLegal = Menu::updateOrCreate(['slug' => 'footer-legal'], ['name' => 'Legal', 'location' => 'footer_col3', 'status' => 1]);
        $legal = [
            ['title' => ['en' => 'Privacy Policy', 'bn' => 'গোপনীয়তা', 'ar' => 'الخصوصية'], 'url' => '/privacy'],
            ['title' => ['en' => 'Terms', 'bn' => 'শর্তাবলী', 'ar' => 'الشروط'], 'url' => '/terms'],
            ['title' => ['en' => 'Refund Policy', 'bn' => 'রিফান্ড', 'ar' => 'الاسترداد'], 'url' => '/refund'],
        ];
        foreach ($legal as $i => $l) {
            MenuItem::updateOrCreate(['menu_id' => $footerLegal->id, 'url' => $l['url']], array_merge($l, ['menu_id' => $footerLegal->id, 'order' => $i + 1, 'status' => 1]));
        }

        $this->command->info('Menu seeded successfully!');
    }
}
