<?php

namespace Database\Seeders;

use App\Models\ExpenseClaim;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExpenseClaimSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->take(3)->get();

        if ($employees->isEmpty()) {
            $this->command->info('ExpenseClaimSeeder: No employees found. Run EmployeeSeeder first!');
            return;
        }

        $claims = [
            [
                'expense_date' => Carbon::today()->subDays(5)->format('Y-m-d'),
                'description' => 'Business trip to Jeddah - flights and accommodation',
                'amount' => 2500.00,
                'currency' => 'SAR',
                'payment_type' => 'reimbursable',
                'status' => 'submitted',
            ],
            [
                'expense_date' => Carbon::today()->subDays(10)->format('Y-m-d'),
                'description' => 'Client lunch meeting expenses',
                'amount' => 450.00,
                'currency' => 'SAR',
                'payment_type' => 'reimbursable',
                'status' => 'approved',
                'reviewed_by' => 1,
                'reviewed_at' => Carbon::today()->subDays(8),
            ],
            [
                'expense_date' => Carbon::today()->subDays(3)->format('Y-m-d'),
                'description' => 'Office supplies purchase',
                'amount' => 350.00,
                'currency' => 'SAR',
                'payment_type' => 'reimbursable',
                'status' => 'pending',
            ],
        ];

        foreach ($claims as $i => $claimData) {
            $employee = $employees->has($i) ? $employees[$i] : $employees->random();
            $claimData['employee_id'] = $employee->id;
            $claimNumber = 'EXP-' . date('Ymd') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $claimData['claim_number'] = $claimNumber;

            ExpenseClaim::updateOrCreate(
                ['employee_id' => $claimData['employee_id'], 'expense_date' => $claimData['expense_date'], 'description' => $claimData['description']],
                $claimData
            );
        }

        $this->command->info('ExpenseClaimSeeder: Created 3 sample expense claims!');
    }
}
