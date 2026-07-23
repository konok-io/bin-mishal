<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InvestorService;
use Illuminate\Database\Seeder;

class InvestorServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'service_key' => 'misa_license',
                'name' => 'MISA License',
                'name_bn' => 'মিসা লাইসেন্স',
                'name_ar' => 'ترخيص وزارة الاستثمار',
                'description' => 'Obtain your MISA (Ministry of Investment) license for business operations in Saudi Arabia. We handle the complete process from application to approval.',
                'description_bn' => 'সৌদি আরবে ব্যবসায়িক কার্যক্রমের জন্য আপনার MISA (বিনিয়োগ মন্ত্রণালয়) লাইসেন্স পান।',
                'description_ar' => 'احصل على ترخيص وزارة الاستثمار لبدء أعمالك في المملكة العربية السعودية.',
                'icon' => 'fas fa-certificate',
                'color' => '#059669',
                'processing_time' => '15-30 business days',
                'required_documents' => [
                    'Passport Copy' => 'Valid passport (minimum 6 months)',
                    'CV/Resume' => 'Updated professional CV',
                    'Business Plan' => 'Detailed business plan',
                    'Financial Statement' => 'Proof of funds',
                    'Office Address' => 'Virtual or physical office contract',
                ],
                'fee_structure' => [
                    'License Fee' => 'SAR 2,000 - 5,000',
                    'Legal Fees' => 'SAR 1,500 - 3,000',
                    'Translation' => 'SAR 500 - 1,000',
                ],
                'sort_order' => 1,
            ],
            [
                'service_key' => 'foreign_investment',
                'name' => 'Foreign Investment',
                'name_bn' => 'বিদেশি বিনিয়োগ',
                'name_ar' => 'الاستثمار الأجنبي',
                'description' => 'Comprehensive support for foreign investors looking to establish or expand their business presence in Saudi Arabia.',
                'description_bn' => 'সৌদি আরবে তাদের ব্যবসায়িক উপস্থিতি স্থাপন বা সম্প্রসারণ করতে চাওয়া বিদেশি বিনিয়োগকারীদের জন্য ব্যাপক সহায়তা।',
                'description_ar' => 'دعم شامل للمستثمرين الأجانب الراغبين في إنشاء أو توسيع أعمالهم في المملكة العربية السعودية.',
                'icon' => 'fas fa-globe',
                'color' => '#3B82F6',
                'processing_time' => '30-60 business days',
                'required_documents' => [
                    'Investment Plan' => 'Detailed investment proposal',
                    'Company Documents' => 'Certificate of incorporation',
                    'Passport' => 'Passport copy of investor',
                    'Tax Clearance' => 'Tax clearance certificate',
                ],
                'fee_structure' => [
                    'Registration' => 'SAR 3,000',
                    'Legal Consultation' => 'SAR 2,500',
                    'Processing' => 'SAR 1,000',
                ],
                'sort_order' => 2,
            ],
            [
                'service_key' => 'company_registration',
                'name' => 'Company Registration',
                'name_bn' => 'কোম্পানি নিবন্ধন',
                'name_ar' => 'تسجيل الشركة',
                'description' => 'Register your company in Saudi Arabia with full legal compliance. We support all company types including LLC, JSC, and branch offices.',
                'description_bn' => 'পূর্ণ আইনি সম্মতি সহ সৌদি আরবে আপনার কোম্পানি নিবন্ধন করুন। আমরা LLC, JSC এবং শাখা অফিস সহ সমস্ত কোম্পানি প্রকার সমর্থন করি।',
                'description_ar' => 'سجل شركتك في المملكة العربية السعودية مع الامتثال القانوني الكامل.',
                'icon' => 'fas fa-building',
                'color' => '#8B5CF6',
                'processing_time' => '10-20 business days',
                'required_documents' => [
                    'Company Name' => 'Proposed company name',
                    'Activities' => 'List of business activities',
                    'Partners' => 'Partner details and shares',
                    'Office' => 'Office address proof',
                ],
                'fee_structure' => [
                    'Registration' => 'SAR 1,500',
                    'Notary' => 'SAR 500',
                    'Publication' => 'SAR 300',
                ],
                'sort_order' => 3,
            ],
            [
                'service_key' => 'branch_registration',
                'name' => 'Branch Registration',
                'name_bn' => 'শাখা নিবন্ধন',
                'name_ar' => 'تسجيل الفرع',
                'description' => 'Register a branch office of your existing foreign company in Saudi Arabia.',
                'description_bn' => 'সৌদি আরবে আপনার বিদ্যমান বিদেশি কোম্পানির একটি শাখা অফিস নিবন্ধন করুন।',
                'description_ar' => 'سجل فرعًا لمكتب شركتك الأجنبية الحالية في المملكة العربية السعودية.',
                'icon' => 'fas fa-sitemap',
                'color' => '#EC4899',
                'processing_time' => '15-25 business days',
                'required_documents' => [
                    'Parent Company Docs' => 'Certificate of incorporation',
                    'Board Resolution' => 'Resolution to open branch',
                    'Power of Attorney' => 'POA for local manager',
                    'Financial Audit' => 'Latest audit report',
                ],
                'fee_structure' => [
                    'Branch Setup' => 'SAR 2,000',
                    'Legal Review' => 'SAR 1,500',
                    'Annual Fees' => 'SAR 1,000',
                ],
                'sort_order' => 4,
            ],
            [
                'service_key' => 'investor_consultation',
                'name' => 'Investor Consultation',
                'name_bn' => 'বিনিয়োগকারী পরামর্শ',
                'name_ar' => 'استشارة المستثمر',
                'description' => 'Get expert consultation on investment opportunities, market analysis, and business strategies for Saudi Arabia.',
                'description_bn' => 'সৌদি আরবের জন্য বিনিয়োগের সুযোগ, বাজার বিশ্লেষণ এবং ব্যবসায়িক কৌশলগুলির উপর বিশেষজ্ঞ পরামর্শ পান।',
                'description_ar' => 'احصل على استشارة الخبراء حول فرص الاستثمار وتحليل السوق واستراتيجيات الأعمال للسعودية.',
                'icon' => 'fas fa-comments',
                'color' => '#F59E0B',
                'processing_time' => '1-3 business days',
                'required_documents' => [
                    'Initial Query' => 'Brief description of your interest',
                    'Background' => 'Your professional background',
                ],
                'fee_structure' => [
                    'Initial Consultation' => 'Free',
                    'Detailed Report' => 'SAR 500',
                    'Ongoing Advisory' => 'SAR 2,000/month',
                ],
                'sort_order' => 5,
            ],
        ];

        foreach ($services as $service) {
            InvestorService::updateOrCreate(
                ['service_key' => $service['service_key']],
                $service
            );
        }
        
        $this->command->info('Investor services seeded successfully.');
    }
}
