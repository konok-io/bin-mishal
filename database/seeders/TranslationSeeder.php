<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            ['group' => 'messages', 'key' => 'welcome', 'en' => 'Welcome to our travel agency', 'bn' => 'আমাদের ট্রাভেল এজেন্সিতে স্বাগতম', 'ar' => 'مرحباً بك في وكالتنا للسفر'],
            ['group' => 'messages', 'key' => 'book_now', 'en' => 'Book Now', 'bn' => 'এখন বুক করুন', 'ar' => 'احجز الآن'],
            ['group' => 'messages', 'key' => 'contact_us', 'en' => 'Contact Us', 'bn' => 'যোগাযোগ করুন', 'ar' => 'اتصل بنا'],
            ['group' => 'messages', 'key' => 'learn_more', 'en' => 'Learn More', 'bn' => 'আরও জানুন', 'ar' => 'اعرف المزيد'],
            ['group' => 'messages', 'key' => 'special_offer', 'en' => 'Special Offer', 'bn' => 'বিশেষ অফার', 'ar' => 'عرض خاص'],
            ['group' => 'services', 'key' => 'umrah', 'en' => 'Umrah Packages', 'bn' => 'উমরাহ প্যাকেজ', 'ar' => 'باقات العمرة'],
            ['group' => 'services', 'key' => 'visa', 'en' => 'Visa Services', 'bn' => 'ভিসা সেবা', 'ar' => 'خدمات التأشيرة'],
            ['group' => 'services', 'key' => 'flight', 'en' => 'Flight Booking', 'bn' => 'ফ্লাইট বুকিং', 'ar' => 'حجز الطيران'],
            ['group' => 'services', 'key' => 'hotel', 'en' => 'Hotel Booking', 'bn' => 'হোটেল বুকিং', 'ar' => 'حجز الفندق'],
            ['group' => 'services', 'key' => 'cargo', 'en' => 'Cargo Services', 'bn' => 'কার্গো সেবা', 'ar' => 'خدمات الشحن'],
            ['group' => 'buttons', 'key' => 'submit', 'en' => 'Submit', 'bn' => 'সাবমিট', 'ar' => 'إرسال'],
            ['group' => 'buttons', 'key' => 'cancel', 'en' => 'Cancel', 'bn' => 'বাতিল', 'ar' => 'إلغاء'],
            ['group' => 'buttons', 'key' => 'save', 'en' => 'Save', 'bn' => 'সংরক্ষণ', 'ar' => 'حفظ'],
            ['group' => 'buttons', 'key' => 'delete', 'en' => 'Delete', 'bn' => 'মুছুন', 'ar' => 'حذف'],
            ['group' => 'buttons', 'key' => 'edit', 'en' => 'Edit', 'bn' => 'সম্পাদনা', 'ar' => 'تعديل'],
        ];

        foreach ($translations as $trans) {
            Translation::updateOrCreate(
                ['group' => $trans['group'], 'key' => $trans['key']],
                [
                    'en' => $trans['en'],
                    'bn' => $trans['bn'],
                    'ar' => $trans['ar'],
                ]
            );
        }

        $this->command->info('TranslationSeeder: Created ' . count($translations) . ' translations!');
    }
}
