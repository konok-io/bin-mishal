<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->take(3)->get();

        if ($employees->isEmpty()) {
            $this->command->info('LeaveRequestSeeder: No employees found. Run EmployeeSeeder first!');
            return;
        }

        $leaves = [
            [
                'employee_id' => null,
                'leave_type' => 'annual',
                'start_date' => now()->addDays(15)->format('Y-m-d'),
                'end_date' => now()->addDays(22)->format('Y-m-d'),
                'total_days' => 7,
                'reason' => 'Family vacation to hometown',
                'status' => 'pending',
            ],
            [
                'employee_id' => null,
                'leave_type' => 'sick',
                'start_date' => now()->addDays(5)->format('Y-m-d'),
                'end_date' => now()->addDays(6)->format('Y-m-d'),
                'total_days' => 2,
                'reason' => 'Medical appointment and recovery',
                'status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'employee_id' => null,
                'leave_type' => 'emergency',
                'start_date' => now()->addDays(2)->format('Y-m-d'),
                'end_date' => now()->addDays(3)->format('Y-m-d'),
                'total_days' => 2,
                'reason' => 'Emergency family matter',
                'status' => 'pending',
            ],
        ];

        foreach ($leaves as $index => $leaveData) {
            if ($employees->has($index)) {
                $leaveData['employee_id'] = $employees[$index]->id;
            } else {
                $leaveData['employee_id'] = $employees->random()->id;
            }

            Leave::updateOrCreate(
                [
                    'employee_id' => $leaveData['employee_id'],
                    'start_date' => $leaveData['start_date'],
                    'leave_type' => $leaveData['leave_type'],
                ],
                $leaveData
            );
        }

        $this->command->info('LeaveRequestSeeder: Created 3 sample leave requests!');
    }
}
