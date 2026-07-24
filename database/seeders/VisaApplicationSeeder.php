<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VisaStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\VisaApplication;
use App\Models\VisaType;
use Illuminate\Database\Seeder;

class VisaApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::take(3)->get();
        $visaTypes = VisaType::take(3)->get();
        $users = User::take(2)->get();

        $applications = [
            [
                'application_no' => 'VISA-240720001',
                'customer_id' => $customers->first()?->id,
                'visa_type_id' => $visaTypes->first()?->id,
                'applicant_name' => 'Ahmed Abdullah',
                'passport_no' => 'A12345678',
                'iqama_no' => 'IQ78901234',
                'sponsor_name' => 'Mohammed Al-Rashid',
                'sponsor_id' => 'SP123456',
                'travel_date' => now()->addDays(30),
                'return_date' => now()->addDays(90),
                'purpose' => 'Employment',
                'status' => VisaStatus::SUBMITTED,
                'government_fee' => 650.00,
                'service_fee' => 350.00,
                'total_amount' => 1000.00,
                'paid_amount' => 1000.00,
                'submission_date' => now()->subDays(5),
                'assigned_to' => $users->first()?->id,
            ],
            [
                'application_no' => 'VISA-240720002',
                'customer_id' => $customers->skip(1)->first()?->id,
                'visa_type_id' => $visaTypes->skip(1)->first()?->id,
                'applicant_name' => 'Fatima Hassan',
                'passport_no' => 'B87654321',
                'iqama_no' => 'IQ56789012',
                'sponsor_name' => 'Hassan Trading Co.',
                'sponsor_id' => 'SP654321',
                'travel_date' => now()->addDays(45),
                'return_date' => now()->addDays(180),
                'purpose' => 'Family Visit',
                'status' => VisaStatus::UNDER_REVIEW,
                'government_fee' => 300.00,
                'service_fee' => 200.00,
                'total_amount' => 500.00,
                'paid_amount' => 500.00,
                'submission_date' => now()->subDays(10),
                'assigned_to' => $users->skip(1)->first()?->id,
            ],
            [
                'application_no' => 'VISA-240720003',
                'customer_id' => $customers->last()?->id,
                'visa_type_id' => $visaTypes->first()?->id,
                'applicant_name' => 'Omar Khalid',
                'passport_no' => 'C11223344',
                'iqama_no' => 'IQ33445566',
                'sponsor_name' => 'Global Industries',
                'sponsor_id' => 'SP112233',
                'travel_date' => now()->addDays(60),
                'return_date' => now()->addDays(365),
                'purpose' => 'Business',
                'status' => VisaStatus::GOVERNMENT_PROCESSING,
                'government_fee' => 850.00,
                'service_fee' => 450.00,
                'total_amount' => 1300.00,
                'paid_amount' => 1300.00,
                'submission_date' => now()->subDays(15),
                'assigned_to' => $users->first()?->id,
            ],
            [
                'application_no' => 'VISA-240720004',
                'customer_id' => $customers->first()?->id,
                'visa_type_id' => $visaTypes->last()?->id,
                'applicant_name' => 'Sara Mohammed',
                'passport_no' => 'D99887766',
                'iqama_no' => 'IQ55667788',
                'sponsor_name' => 'Al-Mohammed Est.',
                'sponsor_id' => 'SP998877',
                'travel_date' => now()->addDays(20),
                'return_date' => now()->addDays(60),
                'purpose' => 'Tourism',
                'status' => VisaStatus::APPROVED,
                'government_fee' => 400.00,
                'service_fee' => 150.00,
                'total_amount' => 550.00,
                'paid_amount' => 550.00,
                'submission_date' => now()->subDays(20),
                'completion_date' => now()->subDays(2),
                'assigned_to' => $users->skip(1)->first()?->id,
            ],
            [
                'application_no' => 'VISA-240720005',
                'customer_id' => $customers->skip(1)->first()?->id,
                'visa_type_id' => $visaTypes->first()?->id,
                'applicant_name' => 'Yusuf Ibrahim',
                'passport_no' => 'E44332211',
                'travel_date' => now()->addDays(15),
                'return_date' => now()->addDays(45),
                'purpose' => 'Transit',
                'status' => VisaStatus::DRAFT,
                'government_fee' => 200.00,
                'service_fee' => 100.00,
                'total_amount' => 300.00,
                'paid_amount' => 0.00,
            ],
        ];

        foreach ($applications as $appData) {
            VisaApplication::updateOrCreate(
                ['application_no' => $appData['application_no']],
                $appData
            );
        }

        $this->command->info('VisaApplicationSeeder: Created ' . count($applications) . ' sample visa applications.');
    }
}
