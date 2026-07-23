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

            // Company
            ['key' => 'company_phone', 'value' => '+9661351234567', 'group' => 'company', 'type' => 'text', 'label' => 'Phone'],
            ['key' => 'company_whatsapp', 'value' => '+966500000100', 'group' => 'company', 'type' => 'text', 'label' => 'WhatsApp'],
            ['key' => 'company_email', 'value' => 'info@binmishal.com', 'group' => 'company', 'type' => 'text', 'label' => 'Email'],
            ['key' => 'company_address', 'value' => 'Al Hufuf, Eastern Province, Saudi Arabia', 'group' => 'company', 'type' => 'text', 'label' => 'Address'],
            ['key' => 'working_hours', 'value' => 'Sat-Thu: 9AM-9PM', 'group' => 'company', 'type' => 'text', 'label' => 'Working Hours'],

            // Business
            ['key' => 'tax_rate', 'value' => '15', 'group' => 'business', 'type' => 'number', 'label' => 'Tax Rate (%)'],
            ['key' => 'currency', 'value' => 'SAR', 'group' => 'business', 'type' => 'text', 'label' => 'Currency'],
            ['key' => 'currency_symbol', 'value' => 'ر.س', 'group' => 'business', 'type' => 'text', 'label' => 'Currency Symbol'],
            ['key' => 'date_format', 'value' => 'd M Y', 'group' => 'business', 'type' => 'text'],
            ['key' => 'timezone', 'value' => 'Asia/Riyadh', 'group' => 'business', 'type' => 'text'],

            // Social
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/binmishal', 'group' => 'social', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/binmishal', 'group' => 'social', 'type' => 'text'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/binmishal', 'group' => 'social', 'type' => 'text'],

            // Booking
            ['key' => 'auto_confirm_booking', 'value' => '0', 'group' => 'booking', 'type' => 'boolean'],
            ['key' => 'require_payment_for_issuing', 'value' => '1', 'group' => 'booking', 'type' => 'boolean'],

            // Invoice
            ['key' => 'invoice_due_days', 'value' => '30', 'group' => 'invoice', 'type' => 'number'],
            ['key' => 'auto_reminder', 'value' => '1', 'group' => 'invoice', 'type' => 'boolean'],

            // Maintenance
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'system', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('Settings created successfully!');
    }
}
