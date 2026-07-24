<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Senior Travel Consultant',
                'title_bn' => 'সিনিয়র ট্রাভেল কনসালট্যান্ট',
                'slug' => 'senior-travel-consultant',
                'department' => 'Operations',
                'location' => 'Riyadh, Saudi Arabia',
                'country' => 'SA',
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'salary_min' => 10000,
                'salary_max' => 15000,
                'salary_visible' => true,
                'description' => 'We are looking for an experienced Travel Consultant.',
                'responsibilities' => 'Handle customer inquiries, book flights',
                'requirements' => '3+ years experience',
                'benefits' => 'Competitive salary',
                'deadline' => now()->addDays(30)->format('Y-m-d'),
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Marketing Manager',
                'title_bn' => 'মার্কেটিং ম্যানেজার',
                'slug' => 'marketing-manager',
                'department' => 'Sales',
                'location' => 'Jeddah, Saudi Arabia',
                'country' => 'SA',
                'employment_type' => 'full_time',
                'experience_level' => 'senior',
                'salary_min' => 15000,
                'salary_max' => 20000,
                'salary_visible' => true,
                'description' => 'Lead our marketing initiatives.',
                'responsibilities' => 'Develop marketing strategies',
                'requirements' => '5+ years experience',
                'benefits' => 'Attractive package',
                'deadline' => now()->addDays(45)->format('Y-m-d'),
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'IT Support Specialist',
                'title_bn' => 'আইটি সাপোর্ট স্পেশালিস্ট',
                'slug' => 'it-support-specialist',
                'department' => 'IT',
                'location' => 'Riyadh, Saudi Arabia',
                'country' => 'SA',
                'employment_type' => 'full_time',
                'experience_level' => 'entry',
                'salary_min' => 7000,
                'salary_max' => 10000,
                'salary_visible' => true,
                'description' => 'Provide technical support.',
                'responsibilities' => 'Troubleshoot issues',
                'requirements' => 'IT certification',
                'benefits' => 'Training opportunities',
                'deadline' => now()->addDays(20)->format('Y-m-d'),
                'status' => 'published',
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($jobs as $jobData) {
            Job::updateOrCreate(['slug' => $jobData['slug']], $jobData);
        }

        $this->command->info('JobPostingSeeder: Created 3 sample job postings!');
    }
}
