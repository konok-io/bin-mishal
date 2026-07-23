<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Customers
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',

            // Bookings
            'bookings.view',
            'bookings.create',
            'bookings.update',
            'bookings.delete',
            'bookings.issue',

            // Visa
            'visas.view',
            'visas.create',
            'visas.update',
            'visas.delete',
            'visas.approve',

            // Invoices
            'invoices.view',
            'invoices.create',
            'invoices.update',
            'invoices.delete',
            'invoices.send',

            // Payments
            'payments.view',
            'payments.create',
            'payments.verify',

            // Reports
            'reports.view',
            'reports.export',

            // Settings
            'settings.view',
            'settings.update',

            // Content
            'content.view',
            'content.create',
            'content.update',
            'content.delete',

            // Careers/HR
            'careers.view',
            'careers.create',
            'careers.update',
            'careers.delete',
            'applications.view',
            'applications.update',
            'applications.shortlist',
            'applications.reject',
            'applications.hire',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $agent = Role::firstOrCreate(['name' => 'agent']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Super Admin - All permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - All permissions except roles.delete
        $admin->givePermissionTo(Permission::where('name', '!=', 'users.delete')->get());

        // Manager - Most permissions
        $manager->givePermissionTo([
            'customers.view', 'customers.create', 'customers.update',
            'bookings.view', 'bookings.create', 'bookings.update', 'bookings.issue',
            'visas.view', 'visas.create', 'visas.update', 'visas.approve',
            'invoices.view', 'invoices.create', 'invoices.update',
            'payments.view', 'payments.create', 'payments.verify',
            'reports.view', 'reports.export',
        ]);

        // Agent - Limited permissions
        $agent->givePermissionTo([
            'customers.view', 'customers.create', 'customers.update',
            'bookings.view', 'bookings.create', 'bookings.update',
            'visas.view', 'visas.create', 'visas.update',
            'invoices.view', 'invoices.create',
            'payments.view', 'payments.create',
        ]);

        // Customer - Limited view permissions
        $customer->givePermissionTo([
            'bookings.view',
            'visas.view',
            'invoices.view',
        ]);

        // HR/Recruiter - Careers module only
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->givePermissionTo([
            'careers.view', 'careers.create', 'careers.update', 'careers.delete',
            'applications.view', 'applications.update', 'applications.shortlist', 'applications.reject', 'applications.hire',
            'customers.view', 'customers.create', 'customers.update',
            'content.view', 'content.create', 'content.update',
        ]);
    }
}
