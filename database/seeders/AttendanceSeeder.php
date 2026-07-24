<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::role('employee')->take(5)->get();

        if ($employees->isEmpty()) {
            $this->command->info('AttendanceSeeder: No employees found. Run EmployeeSeeder first!');
            return;
        }

        for ($day = 6; $day >= 0; $day--) {
            $date = Carbon::today()->subDays($day);
            
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($employees as $employee) {
                $checkIn = $date->copy()->setTime(8 + rand(0, 1), rand(0, 59));
                $checkOut = $date->copy()->setTime(17, rand(0, 30));
                
                if (rand(0, 10) > 8) {
                    $checkIn = $checkIn->copy()->addMinutes(rand(15, 45));
                }

                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'check_in' => $checkIn->format('H:i:s'),
                        'check_out' => $checkOut->format('H:i:s'),
                        'status' => 'present',
                        'notes' => null,
                    ]
                );
            }
        }

        $this->command->info('AttendanceSeeder: Created attendance records for ' . $employees->count() . ' employees!');
    }
}
