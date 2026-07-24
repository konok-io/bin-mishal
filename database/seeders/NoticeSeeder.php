<?php

namespace Database\Seeders;

use App\Models\Notice;
use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        $notices = [
            [
                'content' => [
                    'en' => '🎉 Special Offer: Get 20% off on all Umrah packages this month!',
                    'bn' => '🎉 বিশেষ অফার: এই মাসে সকল উমরাহ প্যাকেজে ২০% ছাড়!',
                    'ar' => '🎉 عرض خاص: احصل على خصم 20% على جميع باقات العمرة هذا الشهر!',
                ],
                'link_text' => [
                    'en' => 'Book Now',
                    'bn' => 'এখন বুক করুন',
                    'ar' => 'احجز الآن',
                ],
                'link_url' => '/services/umrah',
                'type' => 'success',
                'priority' => 1,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth()->addDays(7),
                'visibility' => ['en', 'bn', 'ar'],
                'is_active' => true,
            ],
            [
                'content' => [
                    'en' => '📢 New Office Hours: Saturday to Thursday, 9 AM - 6 PM',
                    'bn' => '📢 নতুন অফিস সময়: শনিবার থেকে বৃহস্পতিবার, সকাল ৯টা - বিকেল ৬টা',
                    'ar' => '📢 ساعات عمل جديدة: السبت إلى الخميس، 9 صباحاً - 6 مساءً',
                ],
                'link_text' => [
                    'en' => 'Contact Us',
                    'bn' => 'যোগাযোগ করুন',
                    'ar' => 'اتصل بنا',
                ],
                'link_url' => '/contact',
                'type' => 'info',
                'priority' => 2,
                'start_date' => now()->subDays(30),
                'end_date' => null,
                'visibility' => ['en', 'bn', 'ar'],
                'is_active' => true,
            ],
            [
                'content' => [
                    'en' => '⚠️ System Maintenance: Scheduled for Saturday 2 AM - 4 AM',
                    'bn' => '⚠️ সিস্টেম রক্ষণাবেক্ষণ: শনিবার সকাল ২টা - ৪টায় নির্ধারিত',
                    'ar' => '⚠️ صيانة النظام: مجدولة ليوم السبت من 2 صباحاً إلى 4 صباحاً',
                ],
                'link_text' => [
                    'en' => 'Learn More',
                    'bn' => 'আরও জানুন',
                    'ar' => 'اعرف المزيد',
                ],
                'link_url' => '/maintenance',
                'type' => 'warning',
                'priority' => 3,
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(3)->setTime(4, 0),
                'visibility' => ['en'],
                'is_active' => true,
            ],
            [
                'content' => [
                    'en' => '🎊 Holiday Notice: Office will be closed on National Day',
                    'bn' => '🎊 ছুটির বিজ্ঞপ্তি: জাতীয় দিবসে অফিস বন্ধ থাকবে',
                    'ar' => '🎊 إشعار عطلة: سيكون المكتب مغلقاً في اليوم الوطني',
                ],
                'link_text' => [
                    'en' => 'View Holidays',
                    'bn' => 'ছুটি দেখুন',
                    'ar' => 'عرض العطلات',
                ],
                'link_url' => '/holidays',
                'type' => 'info',
                'priority' => 2,
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(16),
                'visibility' => ['en', 'bn', 'ar'],
                'is_active' => true,
            ],
            [
                'content' => [
                    'en' => '📱 Download our mobile app for exclusive deals!',
                    'bn' => '📱 এক্সক্লুসিভ ডিলের জন্য আমাদের মোবাইল অ্যাপ ডাউনলোড করুন!',
                    'ar' => '📱 حمّل تطبيقنا للحصول على عروض حصرية!',
                ],
                'link_text' => [
                    'en' => 'Download App',
                    'bn' => 'অ্যাপ ডাউনলোড',
                    'ar' => 'تحميل التطبيق',
                ],
                'link_url' => '/mobile-app',
                'type' => 'info',
                'priority' => 4,
                'start_date' => null,
                'end_date' => null,
                'visibility' => ['en', 'bn', 'ar'],
                'is_active' => true,
            ],
        ];

        foreach ($notices as $noticeData) {
            Notice::updateOrCreate(
                [
                    'content' => $noticeData['content'],
                    'type' => $noticeData['type'],
                ],
                $noticeData
            );
        }

        $this->command->info('NoticeSeeder: Created ' . count($notices) . ' sample notices!');
    }
}
