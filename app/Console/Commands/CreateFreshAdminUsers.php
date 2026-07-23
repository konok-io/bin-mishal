<?php

namespace App\Console\Commands;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateFreshAdminUsers extends Command
{
    protected $signature = 'admin:create-fresh-users';
    protected $description = 'Delete all users and create fresh admin users';

    public function handle(): int
    {
        $this->info('Starting fresh admin user creation...');

        // Create roles
        $roles = ['super_admin', 'admin', 'employee', 'customer'];
        foreach ($roles as $name) {
            Role::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['name' => $name]
            );
        }
        $this->info('✓ Roles created');

        // Delete all users
        User::truncate();
        $this->info('✓ All users deleted');

        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@konok.io',
            'password' => '@rsm@k@1A',
            'user_type' => UserType::SUPER_ADMIN,
            'status' => UserStatus::ACTIVE,
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');
        $this->info('✓ Super Admin created: admin@konok.io / @rsm@k@1A');

        // Create Admin
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@binmishal.com',
            'password' => 'admin123',
            'user_type' => UserType::ADMIN,
            'status' => UserStatus::ACTIVE,
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        $this->info('✓ Admin created: admin@binmishal.com / admin123');

        // Create Employee
        $employee = User::create([
            'name' => 'Employee',
            'email' => 'employee@binmishal.com',
            'password' => 'employee123',
            'user_type' => UserType::EMPLOYEE,
            'status' => UserStatus::ACTIVE,
            'role' => 'employee',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $employee->assignRole('employee');
        $this->info('✓ Employee created: employee@binmishal.com / employee123');

        $this->newLine();
        $this->info('All admin users created successfully!');

        return Command::SUCCESS;
    }
}
