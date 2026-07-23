<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default payroll settings
        $defaultSettings = [
            // Salary components
            ['key' => 'basic_salary_percent', 'value' => '60', 'type' => 'percentage', 'description' => 'Basic salary as percentage of gross'],
            ['key' => 'housing_allowance_percent', 'value' => '25', 'type' => 'percentage', 'description' => 'Housing allowance as percentage'],
            ['key' => 'transport_allowance_percent', 'value' => '10', 'type' => 'percentage', 'description' => 'Transport allowance as percentage'],
            ['key' => 'food_allowance_fixed', 'value' => '300', 'type' => 'currency', 'description' => 'Fixed food allowance (SAR)'],
            
            // Deductions
            ['key' => 'gosi_employee_percent', 'value' => '10', 'type' => 'percentage', 'description' => 'GOSI employee deduction'],
            ['key' => 'gosi_employer_percent', 'value' => '12', 'type' => 'percentage', 'description' => 'GOSI employer contribution'],
            ['key' => 'health_insurance_monthly', 'value' => '100', 'type' => 'currency', 'description' => 'Monthly health insurance deduction'],
            
            // Overtime
            ['key' => 'overtime_rate_multiplier', 'value' => '1.5', 'type' => 'multiplier', 'description' => 'Overtime pay multiplier'],
            ['key' => 'overtime_min_hours', 'value' => '0', 'type' => 'hours', 'description' => 'Minimum hours before overtime applies'],
            
            // Leave
            ['key' => 'annual_leave_days', 'value' => '21', 'type' => 'days', 'description' => 'Annual leave entitlement'],
            ['key' => 'sick_leave_days', 'value' => '14', 'type' => 'days', 'description' => 'Sick leave entitlement'],
            ['key' => 'unpaid_leave_days', 'value' => '5', 'type' => 'days', 'description' => 'Unpaid leave allowed per year'],
            
            // Other
            ['key' => 'currency', 'value' => 'SAR', 'type' => 'text', 'description' => 'Payroll currency'],
            ['key' => 'payroll_day', 'value' => '27', 'type' => 'day_of_month', 'description' => 'Day of month for payroll'],
            ['key' => 'late_grace_minutes', 'value' => '15', 'type' => 'minutes', 'description' => 'Grace period for late arrival'],
        ];

        // Create payroll records for settings
        foreach ($defaultSettings as $setting) {
            \App\Models\PayrollSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description'],
                ]
            );
        }

        $this->command->info('Payroll settings seeded successfully!');
    }
}
