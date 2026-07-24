<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PayrollRecordSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->get();

        if ($employees->isEmpty()) {
            $this->command->info('PayrollRecordSeeder: No employees found. Run EmployeeSeeder first!');
            return;
        }

        $processor = User::first();

        foreach ($employees as $employee) {
            $periodStart = Carbon::today()->startOfMonth()->format('Y-m-d');
            $periodEnd = Carbon::today()->endOfMonth()->format('Y-m-d');
            
            $basicSalary = $employee->salary;
            $allowances = [
                'housing' => $basicSalary * 0.25,
                'transport' => $basicSalary * 0.10,
                'food' => 300,
            ];
            $deductions = [
                'gosi' => $basicSalary * 0.10,
                'health_insurance' => 100,
            ];
            $bonus = rand(0, 1) ? rand(500, 2000) : 0;
            $lateDays = rand(0, 3);
            $lateDeduction = $lateDays * 50;

            Payroll::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'period_start' => $periodStart,
                ],
                [
                    'period_end' => $periodEnd,
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'bonus' => $bonus,
                    'late_days' => $lateDays,
                    'late_deduction' => $lateDeduction,
                    'net_salary' => $basicSalary + array_sum($allowances) + $bonus - array_sum($deductions) - $lateDeduction,
                    'status' => 'paid',
                    'processed_by' => $processor ? $processor->id : 1,
                    'processed_at' => now()->subDays(5),
                    'paid_at' => now()->subDays(3),
                ]
            );
        }

        $this->command->info('PayrollRecordSeeder: Created payroll records for ' . $employees->count() . ' employees!');
    }
}
