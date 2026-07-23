<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = [
            'super_admin' => 'Full system access',
            'admin' => 'Administrative access',
            'accountant' => 'Financial operations',
            'agent' => 'Sales and customer service',
            'customer' => 'Customer portal access',
        ];

        foreach ($roles as $name => 'description') {
            Role::firstOrCreate(['name' => $name]);
        }

        // Create Permissions
        $permissions = [
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Customers
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',
            
            // Bookings
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'bookings.issue',
            'bookings.cancel',
            'bookings.delete',
            
            // Visas
            'visas.view',
            'visas.create',
            'visas.edit',
            'visas.approve',
            'visas.reject',
            'visas.delete',
            
            // Flights
            'flights.view',
            'flights.create',
            'flights.edit',
            'flights.delete',
            
            // Umrah
            'umrah.view',
            'umrah.create',
            'umrah.edit',
            'umrah.delete',
            
            // Leads
            'leads.view',
            'leads.create',
            'leads.edit',
            'leads.convert',
            'leads.delete',
            
            // Invoices
            'invoices.view',
            'invoices.create',
            'invoices.edit',
            'invoices.delete',
            
            // Payments
            'payments.view',
            'payments.create',
            'payments.verify',
            'payments.refund',
            
            // Reports
            'reports.view',
            'reports.export',
            
            // Settings
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

        $accountant = Role::findByName('accountant');
        $accountant->givePermissionTo([
            'customers.view',
            'bookings.view',
            'invoices.view',
            'invoices.create',
            'invoices.edit',
            'payments.view',
            'payments.create',
            'payments.verify',
            'payments.refund',
            'reports.view',
            'reports.export',
        ]);

        $agent = Role::findByName('agent');
        $agent->givePermissionTo([
            'customers.view',
            'customers.create',
            'customers.edit',
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'visas.view',
            'visas.create',
            'visas.edit',
            'flights.view',
            'flights.create',
            'umrah.view',
            'leads.view',
            'leads.create',
            'leads.edit',
            'leads.convert',
            'invoices.view',
            'payments.view',
            'payments.create',
        ]);
    }
}
