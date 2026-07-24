<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Sarah Johnson', 'name_bn' => 'সারাহ জনসন', 'designation' => 'Business Executive', 'designation_bn' => 'ব্যবসায়িক নির্বাহী', 'company' => 'Tech Corp', 'quote' => 'Excellent service!', 'rating' => 5, 'is_featured' => true, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Ahmed Al-Mansour', 'name_bn' => 'আহমেদ আল-মনসুর', 'designation' => 'CEO', 'company' => 'Al-Mansour Holdings', 'quote' => 'Their attention to detail is outstanding.', 'rating' => 5, 'is_featured' => true, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Maria Garcia', 'name_bn' => 'মারিয়া গার্সিয়া', 'designation' => 'Travel Blogger', 'company' => 'Wanderlust Tales', 'quote' => 'This agency never disappoints.', 'rating' => 5, 'is_featured' => false, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'David Chen', 'name_bn' => 'ডেভিড চেন', 'designation' => 'Operations Manager', 'company' => 'Global Trading Co.', 'quote' => 'The visa processing service is incredibly fast.', 'rating' => 4, 'is_featured' => false, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Priya Sharma', 'name_bn' => 'প্রিয়া শর্মা', 'designation' => 'HR Director', 'company' => 'Sunrise Industries', 'quote' => 'Professional, reliable, and always responsive.', 'rating' => 5, 'is_featured' => true, 'is_active' => true, 'sort_order' => 5],
        ];
        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['name' => $t['name'], 'company' => $t['company']], $t);
        }
        $this->command->info('TestimonialSeeder: Created 5 sample testimonials!');
    }
}
