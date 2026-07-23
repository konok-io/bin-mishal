<?php

namespace Database\Seeders;

use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use Illuminate\Database\Seeder;

class DefaultMenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->createMainMenu();
        $this->createFooterMenus();
    }

    protected function createMainMenu(): void
    {
        $menu = Menu::firstOrCreate(
            ['slug' => 'main'],
            [
                'name' => 'Main Navigation',
                'location' => 'header',
                'status' => true,
            ]
        );

        // Home
        $home = MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title->en' => 'Home'],
            [
                'title' => ['en' => 'Home', 'bn' => 'হোম', 'ar' => 'الرئيسية'],
                'type' => 'custom',
                'url' => '/',
                'icon' => 'heroicons-o-home',
                'order' => 1,
                'status' => true,
            ]
        );

        // Services (with children)
        $services = MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title->en' => 'Services'],
            [
                'title' => ['en' => 'Services', 'bn' => 'সেবাসমূহ', 'ar' => 'الخدمات'],
                'type' => 'custom',
                'url' => '/services',
                'icon' => 'heroicons-o-cube',
                'is_mega' => true,
                'mega_column' => 4,
                'order' => 2,
                'status' => true,
            ]
        );

        // Service children
        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'parent_id' => $services->id, 'title->en' => 'Umrah Packages'],
            [
                'title' => ['en' => 'Umrah Packages', 'bn' => 'ওমরাহ প্যাকেজ', 'ar' => 'باقات العمرة'],
                'type' => 'route',
                'route_name' => 'umrah',
                'order' => 1,
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'parent_id' => $services->id, 'title->en' => 'Visa Processing'],
            [
                'title' => ['en' => 'Visa Processing', 'bn' => 'ভিসা প্রসেসিং', 'ar' => 'تأشيرات'],
                'type' => 'route',
                'route_name' => 'visa',
                'order' => 2,
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'parent_id' => $services->id, 'title->en' => 'Flight Booking'],
            [
                'title' => ['en' => 'Flight Booking', 'bn' => 'ফ্লাইট বুকিং', 'ar' => 'حجز الطيران'],
                'type' => 'route',
                'route_name' => 'flights',
                'order' => 3,
                'status' => true,
            ]
        );

        // About
        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title->en' => 'About'],
            [
                'title' => ['en' => 'About Us', 'bn' => 'আমাদের সম্পর্কে', 'ar' => 'من نحن'],
                'type' => 'page',
                'page_id' => null,
                'url' => '/about',
                'order' => 3,
                'status' => true,
            ]
        );

        // Contact
        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title->en' => 'Contact'],
            [
                'title' => ['en' => 'Contact', 'bn' => 'যোগাযোগ', 'ar' => 'اتصل بنا'],
                'type' => 'custom',
                'url' => '/contact',
                'icon' => 'heroicons-o-phone',
                'order' => 4,
                'status' => true,
            ]
        );
    }

    protected function createFooterMenus(): void
    {
        // Footer Column 1 - Services
        $footer1 = Menu::firstOrCreate(
            ['slug' => 'footer-1'],
            [
                'name' => 'Footer Links 1',
                'location' => 'footer_col1',
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footer1->id, 'title->en' => 'Umrah Packages'],
            [
                'title' => ['en' => 'Umrah Packages', 'bn' => 'ওমরাহ', 'ar' => 'باقات العمرة'],
                'type' => 'route',
                'route_name' => 'umrah',
                'order' => 1,
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footer1->id, 'title->en' => 'Visa Services'],
            [
                'title' => ['en' => 'Visa Services', 'bn' => 'ভিসা সেবা', 'ar' => 'خدمات التأشيرة'],
                'type' => 'route',
                'route_name' => 'visa',
                'order' => 2,
                'status' => true,
            ]
        );

        // Footer Column 2 - Company
        $footer2 = Menu::firstOrCreate(
            ['slug' => 'footer-2'],
            [
                'name' => 'Footer Links 2',
                'location' => 'footer_col2',
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footer2->id, 'title->en' => 'About Us'],
            [
                'title' => ['en' => 'About Us', 'bn' => 'আমাদের সম্পর্কে', 'ar' => 'من نحن'],
                'type' => 'page',
                'url' => '/about',
                'order' => 1,
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footer2->id, 'title->en' => 'Contact Us'],
            [
                'title' => ['en' => 'Contact Us', 'bn' => 'যোগাযোগ', 'ar' => 'اتصل بنا'],
                'type' => 'custom',
                'url' => '/contact',
                'order' => 2,
                'status' => true,
            ]
        );

        // Footer Column 3 - Legal
        $footer3 = Menu::firstOrCreate(
            ['slug' => 'footer-3'],
            [
                'name' => 'Footer Links 3',
                'location' => 'footer_col3',
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footer3->id, 'title->en' => 'Privacy Policy'],
            [
                'title' => ['en' => 'Privacy Policy', 'bn' => 'গোপনীয়তা', 'ar' => 'الخصوصية'],
                'type' => 'page',
                'url' => '/privacy',
                'order' => 1,
                'status' => true,
            ]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footer3->id, 'title->en' => 'Terms of Service'],
            [
                'title' => ['en' => 'Terms of Service', 'bn' => 'শর্তাবলী', 'ar' => 'الشروط'],
                'type' => 'page',
                'url' => '/terms',
                'order' => 2,
                'status' => true,
            ]
        );
    }
}
