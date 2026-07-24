<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = Job::where('status', 'published')->take(2)->get();
        if ($jobs->isEmpty()) {
            $this->command->info('JobApplicationSeeder: No job postings found.');
            return;
        }
        $apps = [
            ['full_name' => 'Ahmed Al-Rashid', 'email' => 'ahmed.alrashid@email.com', 'phone' => '+966501234567', 'cover_letter' => 'I am excited to apply for this position.', 'cv_path' => 'cvs/sample-cv-1.pdf', 'status' => 'received', 'applied_at' => now()->subDays(5)],
            ['full_name' => 'Fatima Hassan', 'email' => 'fatima.hassan@email.com', 'phone' => '+966502345678', 'cover_letter' => 'My background makes me a perfect fit.', 'cv_path' => 'cvs/sample-cv-2.pdf', 'status' => 'shortlisted', 'applied_at' => now()->subDays(3)],
            ['full_name' => 'Mohammad Ali', 'email' => 'mohammad.ali@email.com', 'phone' => '+966503456789', 'cover_letter' => 'I have extensive experience.', 'cv_path' => 'cvs/sample-cv-3.pdf', 'status' => 'received', 'applied_at' => now()->subDays(2)],
        ];
        foreach ($apps as $i => $appData) {
            $job = $jobs->has($i) ? $jobs[$i] : $jobs->random();
            $appData['job_id'] = $job->id;
            JobApplication::updateOrCreate(['email' => $appData['email'], 'job_id' => $appData['job_id']], $appData);
        }
        $this->command->info('JobApplicationSeeder: Created sample job applications!');
    }
}
