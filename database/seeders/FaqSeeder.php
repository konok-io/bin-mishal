<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'How do I book a flight?', 'question_bn' => 'ফ্লাইট বুকিং কিভাবে করব?', 'answer' => 'You can book through our website, app, or call our customer service.', 'category' => 'flight', 'is_active' => true, 'sort_order' => 1],
            ['question' => 'What documents do I need for Umrah?', 'question_bn' => 'উমরাহের জন্য কি লাগবে?', 'answer' => 'You need a valid passport, Umrah visa, vaccination certificate.', 'category' => 'umrah', 'is_active' => true, 'sort_order' => 2],
            ['question' => 'How long does visa processing take?', 'question_bn' => 'ভিসা প্রসেসিং কত দিন?', 'answer' => 'Typically 3-7 business days depending on visa type.', 'category' => 'visa', 'is_active' => true, 'sort_order' => 3],
            ['question' => 'What payment methods do you accept?', 'question_bn' => 'কি পেমেন্ট গ্রহণ করেন?', 'answer' => 'Credit cards, debit cards, bank transfers, and cash.', 'category' => 'payment', 'is_active' => true, 'sort_order' => 4],
            ['question' => 'Can I cancel my booking?', 'question_bn' => 'বুকিং বাতিল করতে পারব?', 'answer' => 'Yes, with applicable cancellation fees.', 'category' => 'general', 'is_active' => true, 'sort_order' => 5],
            ['question' => 'Do you offer cargo services?', 'question_bn' => 'কার্গো সার্ভিস দেন?', 'answer' => 'Yes, air freight, sea freight, and land transport.', 'category' => 'cargo', 'is_active' => true, 'sort_order' => 6],
        ];
        foreach ($faqs as $faqData) {
            Faq::updateOrCreate(['question' => $faqData['question'], 'category' => $faqData['category']], $faqData);
        }
        $this->command->info('FaqSeeder: Created sample FAQs!');
    }
}
