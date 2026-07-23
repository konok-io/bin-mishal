<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\VisaType;
use Illuminate\Database\Seeder;

class VisaTypeSeeder extends Seeder
{
    public function run(): void
    {
        $visaTypes = [
            [
                'name' => 'Exit/Re-entry Visa (Single)',
                'name_bn' => 'এক্সিট/রি-এন্ট্রি ভিসা (সিঙ্গেল)',
                'name_ar' => 'تأشيرة خروج وإعادة دخول (مرة واحدة)',
                'slug' => 'exit-reentry-single',
                'country' => 'Saudi Arabia',
                'category' => 'exit_reentry',
                'description' => 'Single exit and re-entry visa allows you to leave Saudi Arabia once and return before the visa expires.',
                'description_bn' => 'সিঙ্গেল এক্সিট এবং রি-এন্ট্রি ভিসা আপনাকে সৌদি আরব ছেড়ে যেতে এবং ভিসার মেয়াদ শেষ হওয়ার আগে ফিরে আসতে দেয়।',
                'description_ar' => 'تأشيرة الخروج وإعادة الدخول لمرة واحدة تسمح لك بمغادرة المملكة العربية السعودية والعودة قبل انتهاء صلاحية التأشيرة.',
                'processing_days' => 7,
                'government_fee' => 200,
                'service_fee' => 100,
                'total_fee' => 300,
                'required_documents' => json_encode([
                    'Valid passport (6+ months)',
                    'Iqama copy',
                    'Passport size photos',
                    'Sponsor NOC',
                ]),
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Exit/Re-entry Visa (Multiple)',
                'name_bn' => 'এক্সিট/রি-এন্ট্রি ভিসা (মাল্টিপল)',
                'name_ar' => 'تأشيرة خروج وإعادة دخول (متعددة)',
                'slug' => 'exit-reentry-multiple',
                'country' => 'Saudi Arabia',
                'category' => 'exit_reentry',
                'description' => 'Multiple exit and re-entry visa allows multiple exits and re-entries during the validity period.',
                'description_bn' => 'মাল্টিপল এক্সিট এবং রি-এন্ট্রি ভিসা আপনাকে বৈধতার মেয়াদে একাধিকবার যাতায়াত করতে দেয়।',
                'description_ar' => 'تأشيرة الخروج وإعادة الدخول المتعددة تسمح بعدة مغادرات وعودات خلال فترة الصلاحية.',
                'processing_days' => 14,
                'government_fee' => 500,
                'service_fee' => 150,
                'total_fee' => 650,
                'required_documents' => json_encode([
                    'Valid passport (6+ months)',
                    'Iqama copy',
                    'Passport size photos (6)',
                    'Sponsor NOC',
                    'Salary certificate',
                ]),
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Final Exit Visa',
                'name_bn' => 'ফাইনাল এক্সিট ভিসা',
                'name_ar' => 'تأشيرة الخروج النهائي',
                'slug' => 'final-exit',
                'country' => 'Saudi Arabia',
                'category' => 'final_exit',
                'description' => 'Final exit visa for leaving Saudi Arabia permanently.',
                'description_bn' => 'সৌদি আরব থেকে স্থায়ীভাবে বেরিয়ে যাওয়ার জন্য ফাইনাল এক্সিট ভিসা।',
                'description_ar' => 'تأشيرة الخروج النهائي لمغادرة المملكة العربية السعودية بشكل دائم.',
                'processing_days' => 3,
                'government_fee' => 100,
                'service_fee' => 50,
                'total_fee' => 150,
                'required_documents' => json_encode([
                    'Valid passport',
                    'Iqama copy',
                    'No-objection letter from employer',
                    'Final settlement clearance',
                ]),
                'is_featured' => false,
                'status' => 'active',
            ],
            [
                'name' => 'Family Visit Visa',
                'name_bn' => 'পরিবারিক ভিজিট ভিসা',
                'name_ar' => 'تأشيرة زيارة عائلية',
                'slug' => 'family-visit',
                'country' => 'Saudi Arabia',
                'category' => 'family_visit',
                'description' => 'Visit visa for family members to visit their sponsors in Saudi Arabia.',
                'description_bn' => 'সৌদি আরবে তাদের স্পনসরদের দেখতে আসার জন্য পরিবারের সদস্যদের জন্য ভিজিট ভিসা।',
                'description_ar' => 'تأشيرة زيارة لأفراد الأسرة لزيارة رعاةهم في المملكة العربية السعودية.',
                'processing_days' => 21,
                'government_fee' => 300,
                'service_fee' => 200,
                'total_fee' => 500,
                'required_documents' => json_encode([
                    'Valid passport (6+ months)',
                    'Passport size photos',
                    'Sponsor\'s Iqama copy',
                    'Family relationship documents',
                    'Invitation letter',
                ]),
                'is_featured' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Umrah Visa',
                'name_bn' => 'উমরাহ ভিসা',
                'name_ar' => 'تأشيرة عمرة',
                'slug' => 'umrah',
                'country' => 'Saudi Arabia',
                'category' => 'umrah',
                'description' => 'Special visa for performing Umrah pilgrimage.',
                'description_bn' => 'উমরাহ তীর্থযাত্রা সম্পাদনের জন্য বিশেষ ভিসা।',
                'description_ar' => 'تأشيرة خاصة لأداء فريضة العمرة.',
                'processing_days' => 5,
                'government_fee' => 300,
                'service_fee' => 100,
                'total_fee' => 400,
                'required_documents' => json_encode([
                    'Valid passport (6+ months)',
                    'Passport size photos (white background)',
                    'Vaccination certificate (Meningitis)',
                    'Accommodation booking',
                    'Round-trip ticket',
                ]),
                'is_featured' => true,
                'status' => 'active',
            ],
        ];

        foreach ($visaTypes as $visaType) {
            VisaType::firstOrCreate(['slug' => $visaType['slug']], $visaType);
        }

        $this->command->info('Visa types created successfully!');
    }
}
