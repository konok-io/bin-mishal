<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@binmishal.com'],
            [
                'name' => 'Super Admin',
                'name_bn' => 'সুপার অ্যাডমিন',
                'name_ar' => 'مدير عام',
                'phone' => '+966500000001',
                'whatsapp' => '+966500000001',
                'password' => Hash::make('password'),
                'user_type' => 'super_admin',
                'nationality' => 'Saudi Arabia',
                'city' => 'Riyadh',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@binmishal.com'],
            [
                'name' => 'Admin User',
                'name_bn' => 'অ্যাডমিন ইউজার',
                'name_ar' => 'مدير',
                'phone' => '+966500000002',
                'whatsapp' => '+966500000002',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'nationality' => 'Bangladesh',
                'city' => 'Dammam',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@binmishal.com'],
            [
                'name' => 'Manager User',
                'name_bn' => 'ম্যানেজার ইউজার',
                'name_ar' => 'مدير',
                'phone' => '+966500000003',
                'whatsapp' => '+966500000003',
                'password' => Hash::make('password'),
                'user_type' => 'employee',
                'nationality' => 'Bangladesh',
                'city' => 'Al Hufuf',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('manager');

        // Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@binmishal.com'],
            [
                'name' => 'Agent User',
                'name_bn' => 'এজেন্ট ইউজার',
                'name_ar' => 'وكيل',
                'phone' => '+966500000004',
                'whatsapp' => '+966500000004',
                'password' => Hash::make('password'),
                'user_type' => 'employee',
                'nationality' => 'Bangladesh',
                'city' => 'Al Hufuf',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $agent->assignRole('agent');

        $this->command->info('Admin users created successfully!');
    }
}
