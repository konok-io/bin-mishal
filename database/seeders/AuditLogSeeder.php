<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(3)->get();
        if ($users->isEmpty()) {
            $this->command->info('AuditLogSeeder: No users found.');
            return;
        }

        $actions = ['created', 'updated', 'deleted', 'viewed'];
        $modules = ['Customer', 'Booking', 'Invoice', 'Payment', 'Lead', 'Employee', 'User', 'Setting'];
        
        $logs = [];
        for ($i = 0; $i < 15; $i++) {
            $logs[] = [
                'user_id' => $users->random()->id,
                'action' => $actions[array_rand($actions)],
                'module' => $modules[array_rand($modules)],
                'record_id' => rand(1, 100),
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'old_values' => json_encode(['status' => 'pending']),
                'new_values' => json_encode(['status' => 'approved']),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ];
        }

        foreach ($logs as $logData) {
            AuditLog::create($logData);
        }

        $this->command->info('AuditLogSeeder: Created ' . count($logs) . ' audit logs!');
    }
}
