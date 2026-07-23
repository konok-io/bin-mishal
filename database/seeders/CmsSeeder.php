<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BookingType;
use App\Models\BookingConfiguration;
use App\Models\CMS\Menu;
use App\Models\CMS\MenuItem;
use App\Models\CMS\Page;
use App\Models\CMS\Setting;
use App\Models\HeroTab;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedHeroTabs();
        $this->seedMenu();
        $this->seedPages();
        $this->seedBookingConfigurations();
    }
    
    protected function seedBookingConfigurations(): void
    {
        $configs = [
            [
                'service_type' => 'flight',
                'booking_types' => [BookingType::TICKET->value, BookingType::SEAT->value],
                'is_enabled' => true,
                'requires_confirmation' => true,
                'min_quantity' => 1,
                'max_quantity' => 9,
                'currency' => 'SAR',
                'pricing_model' => 'per_unit',
                'allow_cancellation' => true,
                'cancellation_deadline_days' => 3,
            ],
            [
                'service_type' => 'umrah',
                'booking_types' => [BookingType::UMRAH->value, BookingType::PACKAGE->value],
                'is_enabled' => true,
                'requires_confirmation' => true,
                'min_quantity' => 1,
                'max_quantity' => 20,
                'currency' => 'SAR',
                'pricing_model' => 'fixed',
                'allow_cancellation' => true,
                'cancellation_deadline_days' => 7,
            ],
            [
                'service_type' => 'visa',
                'booking_types' => [BookingType::VISA->value],
                'is_enabled' => true,
                'requires_confirmation' => true,
                'min_quantity' => 1,
                'max_quantity' => 10,
                'currency' => 'SAR',
                'pricing_model' => 'fixed',
                'allow_cancellation' => false,
            ],
            [
                'service_type' => 'cargo',
                'booking_types' => [BookingType::CARGO->value, BookingType::QUANTITY->value],
                'is_enabled' => true,
                'requires_confirmation' => false,
                'min_quantity' => 1,
                'max_quantity' => 1000,
                'currency' => 'SAR',
                'pricing_model' => 'tiered',
                'allow_cancellation' => true,
                'cancellation_deadline_days' => 1,
            ],
            [
                'service_type' => 'appointment',
                'booking_types' => [BookingType::SCHEDULE->value, BookingType::APPOINTMENT->value],
                'is_enabled' => true,
                'requires_confirmation' => true,
                'min_quantity' => 1,
                'max_quantity' => 1,
                'currency' => 'SAR',
                'pricing_model' => 'fixed',
                'allow_cancellation' => true,
                'cancellation_deadline_days' => 1,
            ],
            [
                'service_type' => 'investor',
                'booking_types' => [BookingType::INVESTOR->value],
                'is_enabled' => true,
                'requires_confirmation' => true,
                'min_quantity' => 1,
                'max_quantity' => 1,
                'currency' => 'SAR',
                'pricing_model' => 'fixed',
                'allow_cancellation' => true,
                'cancellation_deadline_days' => 2,
            ],
        ];

        foreach ($configs as $config) {
            BookingConfiguration::updateOrCreate(
                ['service_type' => $config['service_type']],
                $config
            );
        }
        
        $this->command->info('Booking configurations seeded successfully.');
    }

    protected function seedSettings(): void
    {
        $defaults = Setting::defaults();
        
        foreach ($defaults as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        $this->command->info('Settings seeded successfully.');
    }

    protected function seedHeroTabs(): void
    {
        $tabs = [
            [
                'tab_key' => 'flight',
                'label' => ['en' => 'Flight', 'bn' => 'ফ্লাইট', 'ar' => 'رحلة طيران'],
                'title' => ['en' => 'Book Your Flight', 'bn' => 'আপনার ফ্লাইট বুক করুন', 'ar' => 'احجز رحلتك'],
                'subtitle' => ['en' => 'Best prices on all routes to Bangladesh & beyond', 'bn' => 'বাংলাদেশ ও তার বাইরে সকল রুটে সেরা দাম', 'ar' => 'أفضل الأسعار لجميع الرحلات إلى بنغلاديش وما بعدها'],
                'features' => [
                    'en' => ['Best prices on all routes', 'Instant booking confirmation', '24/7 customer support'],
                    'bn' => ['সকল রুটে সেরা দাম', 'তাৎক্ষণিক বুকিং নিশ্চিতকরণ', '২৪/৭ গ্রাহক সহায়তা'],
                    'ar' => ['أفضل الأسعار لجميع المسارات', 'تأكيد فوري للحجز', 'دعم العملاء على مدار الساعة'],
                ],
                'button_text' => ['en' => 'Book Now', 'bn' => 'এখনই বুক করুন', 'ar' => 'احجز الآن'],
                'icon' => 'fas fa-plane',
                'order' => 1,
                'is_active' => true,
                'show_in_nav' => true,
            ],
            [
                'tab_key' => 'umrah',
                'label' => ['en' => 'Umrah', 'bn' => 'উমরাহ', 'ar' => 'عمرة'],
                'title' => ['en' => 'Umrah Packages', 'bn' => 'উমরাহ প্যাকেজ', 'ar' => 'باقات العمرة'],
                'subtitle' => ['en' => 'Complete Umrah packages with visa, hotel, transport & guided tours', 'bn' => 'ভিসা, হোটেল, পরিবহন ও গাইডেড ট্যুর সহ সম্পূর্ণ উমরাহ প্যাকেজ', 'ar' => 'باقات عمرة كاملة مع التأشيرة والفندق والنقل والجولات المصحوبة بمرشدين'],
                'features' => [
                    'en' => ['Licensed Umrah operator', 'Premium hotel accommodations', 'Experienced tour guides'],
                    'bn' => ['লাইসেন্সপ্রাপ্ত উমরাহ অপারেটর', 'প্রিমিয়াম হোটেল সুবিধা', 'অভিজ্ঞ ট্যুর গাইড'],
                    'ar' => ['مشغل عمرة مرخص', 'إقامة فندقية فاخرة', 'مرشدون سياحيون ذوو خبرة'],
                ],
                'button_text' => ['en' => 'View Packages', 'bn' => 'প্যাকেজ দেখুন', 'ar' => 'عرض الباقات'],
                'icon' => 'fas fa-kaaba',
                'order' => 2,
                'is_active' => true,
                'show_in_nav' => true,
            ],
            [
                'tab_key' => 'visa',
                'label' => ['en' => 'Visa', 'bn' => 'ভিসা', 'ar' => 'تأشيرة'],
                'title' => ['en' => 'Visa Processing', 'bn' => 'ভিসা প্রসেসিং', 'ar' => 'معالجة التأشيرات'],
                'subtitle' => ['en' => 'Fast and reliable visa services for Saudi Arabia', 'bn' => 'সৌদি আরবের জন্য দ্রুত ও নির্ভরযোগ্য ভিসা সেবা', 'ar' => 'خدمات تأشيرات سريعة وموثوقة للمملكة العربية السعودية'],
                'features' => [
                    'en' => ['Quick visa processing', 'Expert documentation help', '100% approval guidance'],
                    'bn' => ['দ্রুত ভিসা প্রসেসিং', 'বিশেষজ্ঞ ডকুমেন্টেশন সহায়তা', '১০০% অনুমোদন গাইড'],
                    'ar' => ['معالجة سريعة للتأشيرات', 'مساعدة الخبراء في التوثيق', 'إرشادات للموافقة 100%'],
                ],
                'button_text' => ['en' => 'Apply Now', 'bn' => 'এখনই আবেদন করুন', 'ar' => 'قدم الآن'],
                'icon' => 'fas fa-passport',
                'order' => 3,
                'is_active' => true,
                'show_in_nav' => true,
            ],
            [
                'tab_key' => 'cargo',
                'label' => ['en' => 'Cargo', 'bn' => 'কার্গো', 'ar' => 'شحن'],
                'title' => ['en' => 'Cargo & Logistics', 'bn' => 'কার্গো ও লজিস্টিক্স', 'ar' => 'الشحن والخدمات اللوجستية'],
                'subtitle' => ['en' => 'Ship your goods from Saudi Arabia to Bangladesh safely', 'bn' => 'সৌদি আরব থেকে বাংলাদেশে আপনার পণ্য নিরাপদে পাঠান', 'ar' => 'شحن بضائعك من المملكة العربية السعودية إلى بنغلاديش بأمان'],
                'features' => [
                    'en' => ['Door-to-door delivery', 'Real-time tracking', 'Competitive rates'],
                    'bn' => ['ডোর-টু-ডোর ডেলিভারি', 'রিয়েল-টাইম ট্র্যাকিং', 'প্রতিযোগিতামূলক মূল্য'],
                    'ar' => ['توصيل من الباب إلى الباب', 'تتبع في الوقت الفعلي', 'أسعار تنافسية'],
                ],
                'button_text' => ['en' => 'Calculate Price', 'bn' => 'মূল্য গণনা করুন', 'ar' => 'احسب السعر'],
                'icon' => 'fas fa-box',
                'order' => 4,
                'is_active' => true,
                'show_in_nav' => true,
            ],
            [
                'tab_key' => 'appointment',
                'label' => ['en' => 'Appointment', 'bn' => 'অ্যাপয়েন্টমেন্ট', 'ar' => 'موعد'],
                'title' => ['en' => 'Book Appointment', 'bn' => 'অ্যাপয়েন্টমেন্ট বুক করুন', 'ar' => 'احجز موعدا'],
                'subtitle' => ['en' => 'Schedule your visit to our office for personalized service', 'bn' => 'ব্যক্তিগতকৃত সেবার জন্য আমাদের অফিসে আপনার সফর নির্ধারণ করুন', 'ar' => 'حدد موعد لزيارتك لمكتبنا للحصول على خدمة شخصية'],
                'features' => [
                    'en' => ['Flexible scheduling', 'Multiple branches', 'Priority service'],
                    'bn' => ['নমনীয় সময়সূচী', 'একাধিক শাখা', 'প্রাধান্য সেবা'],
                    'ar' => ['جدولة مرنة', 'فروع متعددة', 'خدمة ذات أولوية'],
                ],
                'button_text' => ['en' => 'Book Now', 'bn' => 'এখনই বুক করুন', 'ar' => 'احجز الآن'],
                'icon' => 'fas fa-calendar-check',
                'order' => 5,
                'is_active' => true,
                'show_in_nav' => true,
            ],
            [
                'tab_key' => 'investor',
                'label' => ['en' => 'Investor', 'bn' => 'বিনিয়োগকারী', 'ar' => 'مستثمر'],
                'title' => ['en' => 'Investment Services', 'bn' => 'বিনিয়োগ সেবা', 'ar' => 'خدمات الاستثمار'],
                'subtitle' => ['en' => 'Business setup, licenses, and consultation for investors', 'bn' => 'বিনিয়োগকারীদের জন্য ব্যবসা স্থাপন, লাইসেন্স এবং পরামর্শ', 'ar' => 'تأسيس الأعمال والتراخيص والاستشارات للمستثمرين'],
                'features' => [
                    'en' => ['MISA License', 'Company Registration', 'Expert Consultation'],
                    'bn' => ['মিসা লাইসেন্স', 'কোম্পানি নিবন্ধন', 'বিশেষজ্ঞ পরামর্শ'],
                    'ar' => ['ترخيص وزارة الاستثمار', 'تسجيل الشركة', 'استشارات متخصصة'],
                ],
                'button_text' => ['en' => 'Learn More', 'bn' => 'আরও জানুন', 'ar' => 'اعرف المزيد'],
                'icon' => 'fas fa-chart-line',
                'order' => 6,
                'is_active' => true,
                'show_in_nav' => true,
            ],
        ];

        foreach ($tabs as $tab) {
            HeroTab::updateOrCreate(
                ['tab_key' => $tab['tab_key']],
                $tab
            );
        }

        $this->command->info('Hero tabs seeded successfully.');
    }

    protected function seedMenu(): void
    {
        // Create Header Menu
        $headerMenu = Menu::updateOrCreate(
            ['slug' => 'header'],
            [
                'name' => 'Header Navigation',
                'location' => Menu::LOCATION_HEADER,
                'status' => true,
            ]
        );

        // Create Footer Menu
        $footerMenu = Menu::updateOrCreate(
            ['slug' => 'footer'],
            [
                'name' => 'Footer Navigation',
                'location' => Menu::LOCATION_FOOTER_COL1,
                'status' => true,
            ]
        );

        $this->command->info('Menus seeded successfully.');
    }

    protected function seedPages(): void
    {
        $homepage = Page::updateOrCreate(
            ['slug->en' => 'home'],
            [
                'title' => ['en' => 'Home', 'bn' => 'হোম', 'ar' => 'الرئيسية'],
                'slug' => ['en' => 'home'],
                'is_homepage' => true,
                'status' => 'published',
                'show_header' => true,
                'show_footer' => true,
                'show_breadcrumb' => false,
            ]
        );

        $this->command->info('Pages seeded successfully.');
    }
}
