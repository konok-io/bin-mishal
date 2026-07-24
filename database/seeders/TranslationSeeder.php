<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            ['group' => 'messages', 'key' => 'welcome', 'value_en' => 'Welcome to our travel agency', 'value_bn' => 'আমাদের ট্রাভেল এজেন্সিতে স্বাগতম', 'value_ar' => 'مرحباً بك في وكالتنا للسفر'],
            ['group' => 'messages', 'key' => 'book_now', 'value_en' => 'Book Now', 'value_bn' => 'এখন বুক করুন', 'value_ar' => 'احجز الآن'],
            ['group' => 'messages', 'key' => 'contact_us', 'value_en' => 'Contact Us', 'value_bn' => 'যোগাযোগ করুন', 'value_ar' => 'اتصل بنا'],
            ['group' => 'messages', 'key' => 'learn_more', 'value_en' => 'Learn More', 'value_bn' => 'আরও জানুন', 'value_ar' => 'اعرف المزيد'],
            ['group' => 'messages', 'key' => 'special_offer', 'value_en' => 'Special Offer', 'value_bn' => 'বিশেষ অফার', 'value_ar' => 'عرض خاص'],
            ['group' => 'services', 'key' => 'umrah', 'value_en' => 'Umrah Packages', 'value_bn' => 'উমরাহ প্যাকেজ', 'value_ar' => 'باقات العمرة'],
            ['group' => 'services', 'key' => 'visa', 'value_en' => 'Visa Services', 'value_bn' => 'ভিসা সেবা', 'value_ar' => 'خدمات التأشيرة'],
            ['group' => 'services', 'key' => 'flight', 'value_en' => 'Flight Booking', 'value_bn' => 'ফ্লাইট বুকিং', 'value_ar' => 'حجز الطيران'],
            ['group' => 'services', 'key' => 'hotel', 'value_en' => 'Hotel Booking', 'value_bn' => 'হোটেল বুকিং', 'value_ar' => 'حجز الفندق'],
            ['group' => 'services', 'key' => 'cargo', 'value_en' => 'Cargo Services', 'value_bn' => 'কার্গো সেবা', 'value_ar' => 'خدمات الشحن'],
            ['group' => 'buttons', 'key' => 'submit', 'value_en' => 'Submit', 'value_bn' => 'সাবমিট', 'value_ar' => 'إرسال'],
            ['group' => 'buttons', 'key' => 'cancel', 'value_en' => 'Cancel', 'value_bn' => 'বাতিল', 'value_ar' => 'إلغاء'],
            ['group' => 'buttons', 'key' => 'save', 'value_en' => 'Save', 'value_bn' => 'সংরক্ষণ', 'value_ar' => 'حفظ'],
            ['group' => 'buttons', 'key' => 'delete', 'value_en' => 'Delete', 'value_bn' => 'মুছুন', 'value_ar' => 'حذف'],
            ['group' => 'buttons', 'key' => 'edit', 'value_en' => 'Edit', 'value_bn' => 'সম্পাদনা', 'value_ar' => 'تعديل'],
        ];

        foreach ($translations as $trans) {
            Translation::updateOrCreate(
                ['group' => $trans['group'], 'key' => $trans['key']],
                [
                    'value_en' => $trans['value_en'],
                    'value_bn' => $trans['value_bn'],
                    'value_ar' => $trans['value_ar'],
                    'source' => 'manual',
                    'status' => 'complete',
                ]
            );
        }

        $this->command->info('TranslationSeeder: Created ' . count($translations) . ' translations!');
    }
}
