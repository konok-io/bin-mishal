<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedPosts();
        $this->seedFaqs();
        $this->seedTestimonials();
        
        $this->command->info('Content seeded successfully.');
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'Travel Tips',
                'name_bn' => 'ভ্রমণ টিপস',
                'name_ar' => 'نصائح السفر',
                'slug' => 'travel-tips',
                'icon' => 'fas fa-plane',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Umrah Guide',
                'name_bn' => 'উমরাহ গাইড',
                'name_ar' => 'دليل العمرة',
                'slug' => 'umrah-guide',
                'icon' => 'fas fa-kaaba',
                'color' => '#059669',
                'sort_order' => 2,
            ],
            [
                'name' => 'Visa Updates',
                'name_bn' => 'ভিসা আপডেট',
                'name_ar' => 'تحديثات التأشيرة',
                'slug' => 'visa-updates',
                'icon' => 'fas fa-passport',
                'color' => '#8B5CF6',
                'sort_order' => 3,
            ],
            [
                'name' => 'Cargo Services',
                'name_bn' => 'কার্গো সার্ভিস',
                'name_ar' => 'خدمات الشحن',
                'slug' => 'cargo-services',
                'icon' => 'fas fa-box',
                'color' => '#F59E0B',
                'sort_order' => 4,
            ],
            [
                'name' => 'Investment News',
                'name_bn' => 'বিনিয়োগ সংবাদ',
                'name_ar' => 'أخبار الاستثمار',
                'slug' => 'investment-news',
                'icon' => 'fas fa-chart-line',
                'color' => '#EC4899',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            PostCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }

    private function seedPosts(): void
    {
        $category = PostCategory::where('slug', 'travel-tips')->first();
        
        $posts = [
            [
                'title' => 'Essential Travel Checklist for Saudi Arabia',
                'title_bn' => 'সৌদি আরবের জন্য প্রয়োজনীয় ভ্রমণ তালিকা',
                'title_ar' => 'قائمة سفر أساسية للسعودية',
                'slug' => 'essential-travel-checklist-saudi-arabia',
                'excerpt' => 'Everything you need to know before traveling to Saudi Arabia - from visa requirements to cultural tips.',
                'excerpt_bn' => 'সৌদি আরবে ভ্রমণের আগে আপনার যা জানা দরকার - ভিসা প্রয়োজনীয়তা থেকে শুরু করে সাংস্কৃতিক টিপস।',
                'excerpt_ar' => 'كل ما تحتاج معرفته قبل السفر إلى المملكة العربية السعودية.',
                'content' => 'This comprehensive guide covers all essential aspects of traveling to Saudi Arabia...',
                'category_id' => $category?->id,
                'is_published' => true,
                'published_at' => now(),
                'is_featured' => true,
            ],
            [
                'title' => 'Best Time to Visit Saudi Arabia',
                'title_bn' => 'সৌদি আরব ভ্রমণের সেরা সময়',
                'title_ar' => 'أفضل وقت لزيارة السعودية',
                'slug' => 'best-time-visit-saudi-arabia',
                'excerpt' => 'Discover the optimal seasons for visiting Saudi Arabia based on weather and events.',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'What are the visa requirements for Saudi Arabia?',
                'question_bn' => 'সৌদি আরবের ভিসা প্রয়োজনীয়তা কী?',
                'question_ar' => 'ما هي متطلبات التأشيرة للسعودية؟',
                'answer' => 'Visa requirements vary by nationality. Most visitors can apply for an e-visa online. Business visas require sponsorship from a Saudi company.',
                'category' => Faq::CATEGORY_VISA,
                'sort_order' => 1,
            ],
            [
                'question' => 'How long does it take to process a Umrah visa?',
                'question_bn' => 'উমরাহ ভিসা প্রসেসিং করতে কতদিন লাগে?',
                'question_ar' => 'كم يستغرق الأمر للحصول على تأشيرة العمرة؟',
                'answer' => 'Umrah visa processing typically takes 3-7 working days. During peak seasons, it may take longer.',
                'category' => Faq::CATEGORY_UMRAH,
                'sort_order' => 2,
            ],
            [
                'question' => 'What is the weight limit for cargo shipments?',
                'question_bn' => 'কার্গো শিপমেন্টের ওজন সীমা কত?',
                'question_ar' => 'ما هو حد وزن الشحنات؟',
                'answer' => 'Our cargo services support shipments from 1kg up to 100kg per package. Larger shipments can be arranged.',
                'category' => Faq::CATEGORY_CARGO,
                'sort_order' => 3,
            ],
            [
                'question' => 'What documents are needed for company registration?',
                'question_bn' => 'কোম্পানি নিবন্ধনের জন্য কী কী নথি প্রয়োজন?',
                'question_ar' => 'ما هي المستندات المطلوبة لتسجيل الشركة؟',
                'answer' => 'Required documents include: Certificate of Incorporation, Board Resolution, Power of Attorney, and valid passport copies.',
                'category' => Faq::CATEGORY_INVESTOR,
                'sort_order' => 4,
            ],
            [
                'question' => 'Can I track my cargo shipment online?',
                'question_bn' => 'আমি কি অনলাইনে আমার কার্গো শিপমেন্ট ট্র্যাক করতে পারব?',
                'question_ar' => 'هل يمكنني تتبع شحنة البضائع عبر الإنترنت؟',
                'answer' => 'Yes, you can track your shipment in real-time using your tracking number on our website.',
                'category' => Faq::CATEGORY_CARGO,
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Mohammad Rahman',
                'name_bn' => 'মোহাম্মদ রহমান',
                'designation' => 'Business Executive',
                'designation_bn' => 'ব্যবসায়িক নির্বাহী',
                'company' => 'Tech Solutions Ltd',
                'quote' => 'Excellent service! Bin Mishal Travels made my Umrah journey seamless and memorable.',
                'quote_bn' => 'চমৎকার সেবা! বিন মিশাল ট্রাভেলস আমার উমরাহ যাত্রা সহজ এবং স্মরণীয় করেছে।',
                'rating' => 5,
                'service_type' => 'umrah',
                'is_featured' => true,
            ],
            [
                'name' => 'Fatima Ahmed',
                'name_bn' => 'ফাতিমা আহমেদ',
                'designation' => 'CEO',
                'company' => 'Global Trade Co',
                'quote' => 'Their investment consulting service helped us establish our company in Saudi Arabia within weeks.',
                'quote_bn' => 'তাদের বিনিয়োগ পরামর্শ সেবা আমাদের সপ্তাহের মধ্যে সৌদি আরবে আমাদের কোম্পানি প্রতিষ্ঠা করতে সাহায্য করেছে।',
                'rating' => 5,
                'service_type' => 'investor',
                'is_featured' => true,
            ],
            [
                'name' => 'Abdul Karim',
                'name_bn' => 'আবদুল করিম',
                'designation' => 'Import/Export Manager',
                'quote' => 'Fast and reliable cargo service. My packages arrived safely and on time every time.',
                'quote_bn' => 'দ্রুত এবং নির্ভরযোগ্য কার্গো সেবা। আমার প্যাকেজগুলি প্রতিবার নিরাপদে এবং সময়মতো পৌঁছেছে।',
                'rating' => 5,
                'service_type' => 'cargo',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['name' => $testimonial['name']], $testimonial);
        }
    }
}
