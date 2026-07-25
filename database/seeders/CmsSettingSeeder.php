<?php

namespace Database\Seeders;

use App\Models\CMS\Setting;
use Illuminate\Database\Seeder;

class CmsSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['group' => 'general', 'key' => 'app_name', 'value' => 'Bin Mishal Travel', 'type' => 'text', 'label' => 'Site Name (English)'],
            ['group' => 'general', 'key' => 'app_name_bn', 'value' => 'বিন মিশাল ট্রাভেল', 'type' => 'text', 'label' => 'Site Name (Bangla)'],
            ['group' => 'general', 'key' => 'app_name_ar', 'value' => 'بن ميثال للسفر', 'type' => 'text', 'label' => 'Site Name (Arabic)'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Your Trusted Travel Partner', 'type' => 'text', 'label' => 'Site Tagline'],
            ['group' => 'general', 'key' => 'site_description', 'value' => '', 'type' => 'textarea', 'label' => 'Site Description'],
            ['group' => 'general', 'key' => 'contact_email', 'value' => 'info@binmishal.com', 'type' => 'text', 'label' => 'Contact Email'],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '+966 XX XXX XXXX', 'type' => 'text', 'label' => 'Contact Phone'],
            ['group' => 'general', 'key' => 'contact_whatsapp', 'value' => '+966 XX XXX XXXX', 'type' => 'text', 'label' => 'WhatsApp Number'],
            ['group' => 'general', 'key' => 'contact_address', 'value' => 'Saudi Arabia', 'type' => 'text', 'label' => 'Address'],
            ['group' => 'general', 'key' => 'working_hours', 'value' => 'Sat-Thu: 9AM-6PM', 'type' => 'text', 'label' => 'Working Hours'],
            ['group' => 'general', 'key' => 'google_maps_embed', 'value' => '', 'type' => 'textarea', 'label' => 'Google Maps Embed Code'],

            // Appearance - Logo
            ['group' => 'appearance', 'key' => 'logo_light', 'value' => '', 'type' => 'file', 'label' => 'Logo (Light Background)'],
            ['group' => 'appearance', 'key' => 'logo_dark', 'value' => '', 'type' => 'file', 'label' => 'Logo (Dark Background)'],
            ['group' => 'appearance', 'key' => 'logo_mobile', 'value' => '', 'type' => 'file', 'label' => 'Mobile Logo'],
            ['group' => 'appearance', 'key' => 'favicon', 'value' => '', 'type' => 'file', 'label' => 'Favicon'],
            ['group' => 'appearance', 'key' => 'og_image', 'value' => '', 'type' => 'file', 'label' => 'OG Image (Social Share)'],

            // Appearance - Colors
            ['group' => 'appearance', 'key' => 'primary_color', 'value' => '#343C90', 'type' => 'text', 'label' => 'Primary Color'],
            ['group' => 'appearance', 'key' => 'secondary_color', 'value' => '#E05522', 'type' => 'text', 'label' => 'Secondary Color'],
            ['group' => 'appearance', 'key' => 'accent_color', 'value' => '#1F2937', 'type' => 'text', 'label' => 'Accent Color'],
            ['group' => 'appearance', 'key' => 'header_cta_text', 'value' => 'Get Quote', 'type' => 'text', 'label' => 'Header CTA Button Text'],
            ['group' => 'appearance', 'key' => 'header_cta_url', 'value' => '/contact', 'type' => 'text', 'label' => 'Header CTA Button URL'],

            // Header Options
            ['group' => 'header', 'key' => 'show_login_button', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Login Button'],
            ['group' => 'header', 'key' => 'show_register_button', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Register Button'],
            ['group' => 'header', 'key' => 'header_sticky', 'value' => '1', 'type' => 'boolean', 'label' => 'Sticky Header'],

            // Footer Options
            ['group' => 'footer', 'key' => 'footer_about_text', 'value' => '', 'type' => 'textarea', 'label' => 'Footer About Text'],
            ['group' => 'footer', 'key' => 'show_social_icons', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Social Icons'],
            ['group' => 'footer', 'key' => 'show_newsletter', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Newsletter Form'],
            ['group' => 'footer', 'key' => 'copyright_text', 'value' => '© 2024 Bin Mishal Travel. All rights reserved.', 'type' => 'text', 'label' => 'Copyright Text'],

            // Social Media
            ['group' => 'social', 'key' => 'facebook_url', 'value' => '', 'type' => 'text', 'label' => 'Facebook URL'],
            ['group' => 'social', 'key' => 'twitter_url', 'value' => '', 'type' => 'text', 'label' => 'Twitter URL'],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => '', 'type' => 'text', 'label' => 'Instagram URL'],
            ['group' => 'social', 'key' => 'youtube_url', 'value' => '', 'type' => 'text', 'label' => 'YouTube URL'],
            ['group' => 'social', 'key' => 'linkedin_url', 'value' => '', 'type' => 'text', 'label' => 'LinkedIn URL'],
            ['group' => 'social', 'key' => 'tiktok_url', 'value' => '', 'type' => 'text', 'label' => 'TikTok URL'],

            // SEO
            ['group' => 'seo', 'key' => 'meta_title', 'value' => '', 'type' => 'text', 'label' => 'Default Meta Title'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => '', 'type' => 'textarea', 'label' => 'Default Meta Description'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => '', 'type' => 'text', 'label' => 'Meta Keywords'],
            ['group' => 'seo', 'key' => 'google_analytics_id', 'value' => '', 'type' => 'text', 'label' => 'Google Analytics ID'],
            ['group' => 'seo', 'key' => 'google_search_console', 'value' => '', 'type' => 'text', 'label' => 'Google Search Console Verification'],
            ['group' => 'seo', 'key' => 'bing_webmaster', 'value' => '', 'type' => 'text', 'label' => 'Bing Webmaster Verification'],

            // WhatsApp & Contact
            ['group' => 'contact', 'key' => 'whatsapp_float', 'value' => '1', 'type' => 'boolean', 'label' => 'Show WhatsApp Float Button'],
            ['group' => 'contact', 'key' => 'whatsapp_number', 'value' => '+966 XX XXX XXXX', 'type' => 'text', 'label' => 'WhatsApp Number'],
            ['group' => 'contact', 'key' => 'whatsapp_message', 'value' => 'Hello! I need help.', 'type' => 'text', 'label' => 'WhatsApp Default Message'],
            ['group' => 'contact', 'key' => 'whatsapp_position', 'value' => 'right', 'type' => 'select', 'label' => 'WhatsApp Button Position'],

            // Booking Settings
            ['group' => 'booking', 'key' => 'booking_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Enable Online Booking'],
            ['group' => 'booking', 'key' => 'booking_confirmation_required', 'value' => '1', 'type' => 'boolean', 'label' => 'Require Booking Confirmation'],
            ['group' => 'booking', 'key' => 'max_passengers', 'value' => '9', 'type' => 'number', 'label' => 'Max Passengers per Booking'],

            // Currency
            ['group' => 'currency', 'key' => 'currency_primary', 'value' => 'SAR', 'type' => 'text', 'label' => 'Primary Currency (KSA)'],
            ['group' => 'currency', 'key' => 'currency_secondary', 'value' => 'BDT', 'type' => 'text', 'label' => 'Secondary Currency (BD)'],
            ['group' => 'currency', 'key' => 'exchange_rate', 'value' => '1', 'type' => 'number', 'label' => 'SAR to BDT Exchange Rate'],

            // Maintenance
            ['group' => 'maintenance', 'key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'label' => 'Enable Maintenance Mode'],
            ['group' => 'maintenance', 'key' => 'maintenance_message', 'value' => 'Site is under maintenance. Please check back soon.', 'type' => 'textarea', 'label' => 'Maintenance Message'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
