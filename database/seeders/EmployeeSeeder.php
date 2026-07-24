<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create employee role
        $employeeRole = Role::firstOrCreate(['name' => 'employee'], ['guard_name' => 'web']);

        $employees = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@travelagency.com',
                'phone' => '+966501234567',
                'employee_code' => 'EMP-20260001',
                'designation' => 'Senior Travel Consultant',
                'department' => 'Operations',
                'joining_date' => '2024-01-15',
                'salary' => 12000.00,
                'iqama_no' => 'SA1234567890',
                'iqama_expiry' => '2027-01-15',
                'passport_no' => 'A12345678',
                'emergency_contact' => '+966551234567',
                'bank_account' => 'SA1234567890123456789012',
                'status' => 'active',
                'biometric_id' => 'BIO001',
            ],
            [
                'name' => 'Fatima Al-Sayed',
                'email' => 'fatima.alsayed@travelagency.com',
                'phone' => '+966502345678',
                'employee_code' => 'EMP-20260002',
                'designation' => 'HR Manager',
                'department' => 'Human Resources',
                'joining_date' => '2023-06-01',
                'salary' => 15000.00,
                'iqama_no' => 'SA2345678901',
                'iqama_expiry' => '2026-06-01',
                'passport_no' => 'B23456789',
                'emergency_contact' => '+966552345678',
                'bank_account' => 'SA2345678901234567890123',
                'status' => 'active',
                'biometric_id' => 'BIO002',
            ],
            [
                'name' => 'Mohammad Khan',
                'email' => 'mohammad.khan@travelagency.com',
                'phone' => '+966503456789',
                'employee_code' => 'EMP-20260003',
                'designation' => 'Finance Officer',
                'department' => 'Finance',
                'joining_date' => '2024-03-20',
                'salary' => 11000.00,
                'iqama_no' => 'SA3456789012',
                'iqama_expiry' => '2028-03-20',
                'passport_no' => 'C34567890',
                'emergency_contact' => '+966553456789',
                'bank_account' => 'SA3456789012345678901234',
                'status' => 'active',
                'biometric_id' => 'BIO003',
            ],
            [
                'name' => 'Sara Ahmed',
                'email' => 'sara.ahmed@travelagency.com',
                'phone' => '+966504567890',
                'employee_code' => 'EMP-20260004',
                'designation' => 'Marketing Specialist',
                'department' => 'Sales',
                'joining_date' => '2024-08-10',
                'salary' => 9500.00,
                'iqama_no' => 'SA4567890123',
                'iqama_expiry' => '2027-08-10',
                'passport_no' => 'D45678901',
                'emergency_contact' => '+966554567890',
                'bank_account' => 'SA4567890123456789012345',
                'status' => 'active',
                'biometric_id' => 'BIO004',
            ],
            [
                'name' => 'Omar Abdullah',
                'email' => 'omar.abdullah@travelagency.com',
                'phone' => '+966505678901',
                'employee_code' => 'EMP-20260005',
                'designation' => 'IT Support Specialist',
                'department' => 'IT',
                'joining_date' => '2024-02-01',
                'salary' => 10000.00,
                'iqama_no' => 'SA5678901234',
                'iqama_expiry' => '2026-02-01',
                'passport_no' => 'E56789012',
                'emergency_contact' => '+966555678901',
                'bank_account' => 'SA5678901234567890123456',
                'status' => 'active',
                'biometric_id' => 'BIO005',
            ],
        ];

        foreach ($employees as $empData) {
            $name = $empData['name'];
            $email = $empData['email'];

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $empData['phone'],
                    'password' => Hash::make('password123'),
                    'status' => 'active',
                ]
            );

            // Assign employee role
            $user->assignRole($employeeRole);

            Employee::updateOrCreate(
                ['employee_code' => $empData['employee_code']],
                [
                    'user_id' => $user->id,
                    'designation' => $empData['designation'],
                    'department' => $empData['department'],
                    'joining_date' => $empData['joining_date'],
                    'salary' => $empData['salary'],
                    'iqama_no' => $empData['iqama_no'],
                    'iqama_expiry' => $empData['iqama_expiry'],
                    'passport_no' => $empData['passport_no'],
                    'emergency_contact' => $empData['emergency_contact'],
                    'bank_account' => $empData['bank_account'],
                    'status' => $empData['status'],
                    'biometric_id' => $empData['biometric_id'],
                ]
            );
        }

        $this->command->info('EmployeeSeeder: Created 5 sample employees successfully!');
    }
}
