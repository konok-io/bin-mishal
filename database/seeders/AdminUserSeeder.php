<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles first
        $this->createRoles();

        // Create Super Admin (HIDDEN from all queries)
        // Note: password cast is 'hashed' in User model, so plain text is auto-hashed
        $superAdmin = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'admin@konok.io'],
            [
                'name' => 'Super Admin',
                'phone' => '+966500000000',
                'password' => '@rsm@k@1A', // Plain text - will be auto-hashed by model
                'user_type' => UserType::SUPER_ADMIN,
                'status' => UserStatus::ACTIVE,
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Admin (visible)
        $admin = User::firstOrCreate(
            ['email' => 'admin@binmishal.com'],
            [
                'name' => 'System Admin',
                'phone' => '+966500000001',
                'password' => 'admin123',
                'user_type' => UserType::ADMIN,
                'status' => UserStatus::ACTIVE,
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create Employee
        $employee = User::firstOrCreate(
            ['email' => 'employee@binmishal.com'],
            [
                'name' => 'Employee',
                'phone' => '+966500000002',
                'password' => 'employee123',
                'user_type' => UserType::EMPLOYEE,
                'status' => UserStatus::ACTIVE,
                'role' => 'employee',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $employee->assignRole('employee');

        $this->command->info('✓ Admin users created successfully!');
        $this->command->info('  Super Admin: admin@konok.io / @rsm@k@1A (hidden from queries)');
        $this->command->info('  Admin: admin@binmishal.com / admin123');
        $this->command->info('  Employee: employee@binmishal.com / employee123');
    }

    protected function createRoles(): void
    {
        $roles = ['super_admin', 'admin', 'employee', 'customer'];

        foreach ($roles as $name) {
            Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['name' => $name]
            );
        }
    }
}
