<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // App Info
            ['key' => 'app_name', 'value' => 'Bin Mishal Travel', 'group' => 'app', 'type' => 'text', 'label' => 'Application Name'],
            ['key' => 'app_name_bn', 'value' => 'বিন মিশাল ট্রাভেল', 'group' => 'app', 'type' => 'text'],
            ['key' => 'app_name_ar', 'value' => 'بن ميثال للسفر', 'group' => 'app', 'type' => 'text'],
            ['key' => 'tagline', 'value' => 'Your Trusted Travel Partner', 'group' => 'app', 'type' => 'text'],
            ['key' => 'tagline_bn', 'value' => 'আপনার বিশ্বস্ত ভ্রমণ অংশীদার', 'group' => 'app', 'type' => 'text'],
            ['key' => 'tagline_ar', 'value' => 'شريكك الموثوق في السفر', 'group' => 'app', 'type' => 'text'],
            ['key' => 'logo_light', 'value' => '', 'group' => 'app', 'type' => 'file', 'label' => 'Logo (Light)'],
            ['key' => 'logo_dark', 'value' => '', 'group' => 'app', 'type' => 'file', 'label' => 'Logo (Dark)'],
            ['key' => 'favicon', 'value' => '', 'group' => 'app', 'type' => 'file', 'label' => 'Favicon'],

            // Company Contact
            ['key' => 'contact_phone', 'value' => '+966 13 XXXXXXX', 'group' => 'contact', 'type' => 'text', 'label' => 'Contact Phone'],
            ['key' => 'contact_email', 'value' => 'info@binmishal.com', 'group' => 'contact', 'type' => 'text', 'label' => 'Contact Email'],
            ['key' => 'company_phone', 'value' => '+9661351234567', 'group' => 'contact', 'type' => 'text', 'label' => 'Phone'],
            ['key' => 'company_whatsapp', 'value' => '+966500000100', 'group' => 'contact', 'type' => 'text', 'label' => 'WhatsApp'],
            ['key' => 'company_address', 'value' => 'Al Hufuf, Eastern Province, Saudi Arabia', 'group' => 'contact', 'type' => 'textarea', 'label' => 'Address'],
            ['key' => 'google_maps_url', 'value' => '', 'group' => 'contact', 'type' => 'text', 'label' => 'Google Maps URL'],
            ['key' => 'working_hours', 'value' => 'Sat-Thu: 9AM-9PM', 'group' => 'contact', 'type' => 'text', 'label' => 'Working Hours'],

            // Header Settings
            ['key' => 'header_cta_text', 'value' => 'Get Quote', 'group' => 'header', 'type' => 'text', 'label' => 'CTA Button Text'],
            ['key' => 'header_cta_url', 'value' => '/contact', 'group' => 'header', 'type' => 'text', 'label' => 'CTA Button URL'],
            ['key' => 'show_login_button', 'value' => '1', 'group' => 'header', 'type' => 'boolean', 'label' => 'Show Login Button'],
            ['key' => 'show_register_button', 'value' => '1', 'group' => 'header', 'type' => 'boolean', 'label' => 'Show Register Button'],

            // Footer Settings
            ['key' => 'footer_tagline', 'value' => 'Your trusted travel partner for Umrah, Visa, and Air Tickets', 'group' => 'footer', 'type' => 'text', 'label' => 'Footer Tagline'],
            ['key' => 'footer_copyright', 'value' => '© 2024 Bin Mishal Travel. All rights reserved.', 'group' => 'footer', 'type' => 'text', 'label' => 'Copyright Text'],
            ['key' => 'footer_powered_by', 'value' => 'Powered by Bin Mishal', 'group' => 'footer', 'type' => 'text', 'label' => 'Powered By Text'],
            ['key' => 'footer_app_link', 'value' => '', 'group' => 'footer', 'type' => 'text', 'label' => 'App Download Link'],
            ['key' => 'footer_play_store_link', 'value' => '', 'group' => 'footer', 'type' => 'text', 'label' => 'Google Play Link'],
            ['key' => 'footer_app_store_link', 'value' => '', 'group' => 'footer', 'type' => 'text', 'label' => 'App Store Link'],

            // Social Links
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/binmishal', 'group' => 'social', 'type' => 'text', 'label' => 'Facebook'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/binmishal', 'group' => 'social', 'type' => 'text', 'label' => 'Instagram'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/binmishal', 'group' => 'social', 'type' => 'text', 'label' => 'Twitter/X'],
            ['key' => 'youtube_url', 'value' => '', 'group' => 'social', 'type' => 'text', 'label' => 'YouTube'],
            ['key' => 'linkedin_url', 'value' => '', 'group' => 'social', 'type' => 'text', 'label' => 'LinkedIn'],
            ['key' => 'tiktok_url', 'value' => '', 'group' => 'social', 'type' => 'text', 'label' => 'TikTok'],

            // Hero Section - Home
            ['key' => 'hero_title', 'value' => 'Your Trusted Travel Partner', 'group' => 'hero_home', 'type' => 'text', 'label' => 'Hero Title'],
            ['key' => 'hero_title_bn', 'value' => 'আপনার বিশ্বস্ত ভ্রমণ অংশীদার', 'group' => 'hero_home', 'type' => 'text', 'label' => 'Hero Title (Bangla)'],
            ['key' => 'hero_title_ar', 'value' => 'شريكك الموثوق في السفر', 'group' => 'hero_home', 'type' => 'text', 'label' => 'Hero Title (Arabic)'],
            ['key' => 'hero_subtitle', 'value' => 'Experience the best Umrah packages, visa services, and air tickets with Bin Mishal Travel', 'group' => 'hero_home', 'type' => 'textarea', 'label' => 'Hero Subtitle'],
            ['key' => 'hero_subtitle_bn', 'value' => 'বিন মিশাল ট্রাভেলের সাথে সেরা ওমরাহ প্যাকেজ, ভিসা সেবা এবং এয়ার টিকিট অনুভব করুন', 'group' => 'hero_home', 'type' => 'textarea', 'label' => 'Hero Subtitle (Bangla)'],
            ['key' => 'hero_subtitle_ar', 'value' => 'اختبر أفضل باقات العمرة وخدمات التأشيرات وتذاكر الطيران مع بن ميثال للسفر', 'group' => 'hero_home', 'type' => 'textarea', 'label' => 'Hero Subtitle (Arabic)'],
            ['key' => 'hero_background_image', 'value' => '', 'group' => 'hero_home', 'type' => 'file', 'label' => 'Background Image'],
            ['key' => 'hero_button_text', 'value' => 'Book Now', 'group' => 'hero_home', 'type' => 'text', 'label' => 'Button Text'],
            ['key' => 'hero_button_url', 'value' => '/contact', 'group' => 'hero_home', 'type' => 'text', 'label' => 'Button URL'],

            // About Section
            ['key' => 'about_title', 'value' => 'About Bin Mishal Travel', 'group' => 'about', 'type' => 'text', 'label' => 'About Title'],
            ['key' => 'about_description', 'value' => 'Bin Mishal Travel is a leading travel agency based in Saudi Arabia, offering comprehensive travel services including Umrah packages, visa processing, flight booking, and cargo services.', 'group' => 'about', 'type' => 'textarea', 'label' => 'About Description'],
            ['key' => 'about_image', 'value' => '', 'group' => 'about', 'type' => 'file', 'label' => 'About Image'],

            // Services Section
            ['key' => 'services_title', 'value' => 'Our Services', 'group' => 'services', 'type' => 'text', 'label' => 'Services Title'],
            ['key' => 'services_subtitle', 'value' => 'We provide comprehensive travel solutions', 'group' => 'services', 'type' => 'text', 'label' => 'Services Subtitle'],

            // Contact Page
            ['key' => 'contact_page_title', 'value' => 'Contact Us', 'group' => 'contact_page', 'type' => 'text', 'label' => 'Page Title'],
            ['key' => 'contact_page_description', 'value' => 'Get in touch with us for any inquiries about our services.', 'group' => 'contact_page', 'type' => 'textarea', 'label' => 'Page Description'],
            ['key' => 'contact_map_embed', 'value' => '', 'group' => 'contact_page', 'type' => 'textarea', 'label' => 'Google Maps Embed Code'],

            // Business Settings
            ['key' => 'tax_rate', 'value' => '15', 'group' => 'business', 'type' => 'number', 'label' => 'Tax Rate (%)'],
            ['key' => 'currency', 'value' => 'SAR', 'group' => 'business', 'type' => 'text', 'label' => 'Currency'],
            ['key' => 'currency_symbol', 'value' => 'ر.س', 'group' => 'business', 'type' => 'text', 'label' => 'Currency Symbol'],
            ['key' => 'date_format', 'value' => 'd M Y', 'group' => 'business', 'type' => 'text'],
            ['key' => 'timezone', 'value' => 'Asia/Riyadh', 'group' => 'business', 'type' => 'text'],

            // Booking Settings
            ['key' => 'auto_confirm_booking', 'value' => '0', 'group' => 'booking', 'type' => 'boolean'],
            ['key' => 'require_payment_for_issuing', 'value' => '1', 'group' => 'booking', 'type' => 'boolean'],

            // Invoice Settings
            ['key' => 'invoice_due_days', 'value' => '30', 'group' => 'invoice', 'type' => 'number'],
            ['key' => 'auto_reminder', 'value' => '1', 'group' => 'invoice', 'type' => 'boolean'],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'maintenance_message', 'value' => 'We are currently under maintenance. Please check back soon.', 'group' => 'system', 'type' => 'textarea', 'label' => 'Maintenance Message'],

            // WhatsApp Widget
            ['key' => 'whatsapp_widget_enabled', 'value' => '1', 'group' => 'widgets', 'type' => 'boolean', 'label' => 'Enable WhatsApp Button'],
            ['key' => 'whatsapp_widget_number', 'value' => '966500000100', 'group' => 'widgets', 'type' => 'text', 'label' => 'WhatsApp Number (no +)'],
            ['key' => 'whatsapp_widget_message', 'value' => "Hi, I'm interested in your services.", 'group' => 'widgets', 'type' => 'text', 'label' => 'Default Message'],
            ['key' => 'whatsapp_widget_position', 'value' => 'left', 'group' => 'widgets', 'type' => 'select', 'label' => 'Position'],

            // AI Chat Widget
            ['key' => 'chat_widget_enabled', 'value' => '1', 'group' => 'widgets', 'type' => 'boolean', 'label' => 'Enable AI Chat Assistant'],
            ['key' => 'chat_widget_position', 'value' => 'right', 'group' => 'widgets', 'type' => 'select', 'label' => 'Position'],
            ['key' => 'chat_widget_welcome', 'value' => 'Assalamu Alaikum! Welcome to Bin Mishal Travel. How can I help you today?', 'group' => 'widgets', 'type' => 'textarea', 'label' => 'Welcome Message'],
            ['key' => 'chat_offline_message', 'value' => "We're currently offline. Please leave a message or contact us via WhatsApp.", 'group' => 'widgets', 'type' => 'textarea', 'label' => 'Offline Message'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('Settings created successfully!');
    }
}
