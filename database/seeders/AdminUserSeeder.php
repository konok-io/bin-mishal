<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@binmishal.com'],
            [
                'name' => 'Super Admin',
                'phone' => '+966500000000',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@travel.com'],
            [
                'name' => 'System Admin',
                'phone' => '+966500000001',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'status' => 'active',
            ]
        );
        $admin->assignRole('admin');

        // Create Accountant
        $accountant = User::firstOrCreate(
            ['email' => 'accountant@binmishal.com'],
            [
                'name' => 'Accountant',
                'phone' => '+966500000002',
                'password' => Hash::make('password123'),
                'user_type' => 'employee',
                'status' => 'active',
            ]
        );
        $accountant->assignRole('accountant');

        // Create Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@binmishal.com'],
            [
                'name' => 'Sales Agent',
                'phone' => '+966500000003',
                'password' => Hash::make('password123'),
                'user_type' => 'employee',
                'status' => 'active',
            ]
        );
        $agent->assignRole('agent');

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@binmishal.com / Password: password123');
    }
}
