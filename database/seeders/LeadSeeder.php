<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(2)->get();

        $leads = [
            [
                'name' => 'Ahmed Al-Farsi',
                'phone' => '+966501234567',
                'whatsapp' => '+966501234567',
                'email' => 'ahmed.alfarsi@email.com',
                'service_interest' => 'Umrah Package',
                'source' => 'website',
                'status' => LeadStatus::NEW,
                'assigned_to' => $users->first()?->id,
                'follow_up_date' => now()->addDays(1),
                'conversion_probability' => 80,
            ],
            [
                'name' => 'Fatima Hassan',
                'phone' => '+966557890123',
                'whatsapp' => '+966557890123',
                'email' => 'fatima.hassan@email.com',
                'service_interest' => 'Visa Processing',
                'source' => 'facebook',
                'status' => LeadStatus::CONTACTED,
                'assigned_to' => $users->skip(1)->first()?->id,
                'follow_up_date' => now()->addDays(3),
                'conversion_probability' => 60,
            ],
            [
                'name' => 'Mohammed Khan',
                'phone' => '+966531234567',
                'whatsapp' => '+966531234567',
                'email' => 'mohammed.khan@email.com',
                'service_interest' => 'Flight Booking',
                'source' => 'referral',
                'status' => LeadStatus::QUALIFIED,
                'assigned_to' => $users->first()?->id,
                'follow_up_date' => now()->addDays(5),
                'conversion_probability' => 90,
            ],
            [
                'name' => 'Sara Ali',
                'phone' => '+966541234567',
                'whatsapp' => '+966541234567',
                'email' => 'sara.ali@email.com',
                'service_interest' => 'Cargo Shipping',
                'source' => 'instagram',
                'status' => LeadStatus::NEW,
                'assigned_to' => $users->skip(1)->first()?->id,
                'follow_up_date' => now()->addDays(2),
                'conversion_probability' => 50,
            ],
            [
                'name' => 'Omar Rashid',
                'phone' => '+966561234567',
                'whatsapp' => '+966561234567',
                'email' => 'omar.rashid@email.com',
                'service_interest' => 'Umrah Package',
                'source' => 'google',
                'status' => LeadStatus::LOST,
                'assigned_to' => $users->first()?->id,
                'lost_reason' => 'Budget constraints',
                'conversion_probability' => 0,
            ],
        ];

        foreach ($leads as $leadData) {
            Lead::updateOrCreate(
                ['email' => $leadData['email']],
                $leadData
            );
        }

        $this->command->info('LeadSeeder: Created ' . count($leads) . ' sample leads.');
    }
}
