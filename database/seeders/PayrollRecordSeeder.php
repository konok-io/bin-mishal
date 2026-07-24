<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PayrollRecordSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::role('employee')->get();

        if ($employees->isEmpty()) {
            $this->command->info('PayrollRecordSeeder: No employees found. Run EmployeeSeeder first!');
            return;
        }

        foreach ($employees as $employee) {
            $periodStart = Carbon::today()->startOfMonth()->format('Y-m-d');
            $periodEnd = Carbon::today()->endOfMonth()->format('Y-m-d');
            
            $basicSalary = 25000;
            $houseRent = $basicSalary * 0.25;
            $transportAllowance = $basicSalary * 0.10;
            $medicalAllowance = 2000;
            $otherAllowances = 1000;
            $overtimePay = rand(0, 1) ? rand(500, 2000) : 0;
            $bonuses = rand(0, 1) ? rand(500, 2000) : 0;
            $taxDeduction = $basicSalary * 0.10;
            $insuranceDeduction = 500;
            $otherDeductions = 200;

            $grossSalary = $basicSalary + $houseRent + $transportAllowance + $medicalAllowance + $otherAllowances + $overtimePay + $bonuses;
            $netSalary = $grossSalary - $taxDeduction - $insuranceDeduction - $otherDeductions;

            Payroll::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'period_start' => $periodStart,
                ],
                [
                    'period_end' => $periodEnd,
                    'basic_salary' => $basicSalary,
                    'house_rent' => $houseRent,
                    'transport_allowance' => $transportAllowance,
                    'medical_allowance' => $medicalAllowance,
                    'other_allowances' => $otherAllowances,
                    'overtime_pay' => $overtimePay,
                    'bonuses' => $bonuses,
                    'tax_deduction' => $taxDeduction,
                    'insurance_deduction' => $insuranceDeduction,
                    'other_deductions' => $otherDeductions,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $netSalary,
                    'status' => 'paid',
                    'paid_at' => now()->subDays(3),
                    'notes' => 'Monthly salary for ' . Carbon::today()->format('F Y'),
                ]
            );
        }

        $this->command->info('PayrollRecordSeeder: Created payroll records for ' . $employees->count() . ' employees!');
    }
}
