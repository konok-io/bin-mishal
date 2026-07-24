<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'user' => [
                    'name' => 'Ahmed Abdullah',
                    'email' => 'ahmed.abdullah@email.com',
                    'phone' => '+966501234567',
                    'whatsapp' => '+966501234567',
                    'user_type' => 'customer',
                    'password' => Hash::make('password123'),
                ],
                'customer' => [
                    'customer_code' => 'CUST-00001',
                    'company_name' => 'Abdullah Trading Co.',
                    'sponsor_name' => 'Khalid Abdullah',
                    'sponsor_id_no' => 'SP123456789',
                    'profession' => 'Business Owner',
                    'work_city' => 'Riyadh',
                    'monthly_income' => 25000.00,
                    'source' => 'website',
                    'lifetime_value' => 45000.00,
                    'total_bookings' => 5,
                ],
            ],
            [
                'user' => [
                    'name' => 'Fatima Hassan',
                    'email' => 'fatima.hassan@email.com',
                    'phone' => '+966557890123',
                    'whatsapp' => '+966557890123',
                    'user_type' => 'customer',
                    'password' => Hash::make('password123'),
                ],
                'customer' => [
                    'customer_code' => 'CUST-00002',
                    'company_name' => null,
                    'sponsor_name' => 'Mohammed Hassan',
                    'sponsor_id_no' => 'SP987654321',
                    'profession' => 'Engineer',
                    'work_city' => 'Jeddah',
                    'monthly_income' => 18000.00,
                    'source' => 'referral',
                    'lifetime_value' => 22000.00,
                    'total_bookings' => 3,
                ],
            ],
            [
                'user' => [
                    'name' => 'Omar Khalid',
                    'email' => 'omar.khalid@email.com',
                    'phone' => '+966531234567',
                    'whatsapp' => '+966531234567',
                    'user_type' => 'customer',
                    'password' => Hash::make('password123'),
                ],
                'customer' => [
                    'customer_code' => 'CUST-00003',
                    'company_name' => 'Khalid Industries',
                    'sponsor_name' => 'Ibrahim Khalid',
                    'sponsor_id_no' => 'SP456789123',
                    'profession' => 'CEO',
                    'work_city' => 'Dammam',
                    'monthly_income' => 50000.00,
                    'source' => 'facebook',
                    'lifetime_value' => 85000.00,
                    'total_bookings' => 8,
                ],
            ],
            [
                'user' => [
                    'name' => 'Sara Mohammed',
                    'email' => 'sara.mohammed@email.com',
                    'phone' => '+966541234567',
                    'whatsapp' => '+966541234567',
                    'user_type' => 'customer',
                    'password' => Hash::make('password123'),
                ],
                'customer' => [
                    'customer_code' => 'CUST-00004',
                    'company_name' => null,
                    'sponsor_name' => 'Ali Mohammed',
                    'sponsor_id_no' => 'SP789123456',
                    'profession' => 'Teacher',
                    'work_city' => 'Riyadh',
                    'monthly_income' => 12000.00,
                    'source' => 'website',
                    'lifetime_value' => 15000.00,
                    'total_bookings' => 2,
                ],
            ],
            [
                'user' => [
                    'name' => 'Yusuf Ibrahim',
                    'email' => 'yusuf.ibrahim@email.com',
                    'phone' => '+966561234567',
                    'whatsapp' => '+966561234567',
                    'user_type' => 'customer',
                    'password' => Hash::make('password123'),
                ],
                'customer' => [
                    'customer_code' => 'CUST-00005',
                    'company_name' => 'Ibrahim Logistics',
                    'sponsor_name' => 'Hassan Ibrahim',
                    'sponsor_id_no' => 'SP321654987',
                    'profession' => 'Logistics Manager',
                    'work_city' => 'Jeddah',
                    'monthly_income' => 20000.00,
                    'source' => 'website',
                    'lifetime_value' => 35000.00,
                    'total_bookings' => 4,
                ],
            ],
        ];

        foreach ($customers as $data) {
            // Check if user with this email already exists
            $existingUser = User::where('email', $data['user']['email'])->first();
            
            if (!$existingUser) {
                // Check if phone already exists
                $phoneExists = User::where('phone', $data['user']['phone'])->exists();
                if ($phoneExists) {
                    // Generate unique phone
                    $data['user']['phone'] = '+9665' . rand(10000000, 99999999);
                    $data['user']['whatsapp'] = $data['user']['phone'];
                }
                
                $user = User::create($data['user']);
            } else {
                $user = $existingUser;
            }

            Customer::updateOrCreate(
                ['user_id' => $user->id],
                array_merge(['user_id' => $user->id], $data['customer'])
            );
        }

        $this->command->info('CustomerSeeder: Created ' . count($customers) . ' sample customers.');
    }
}
