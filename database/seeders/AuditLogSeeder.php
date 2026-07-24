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
        $models = [
            ['type' => 'App\Models\Customer', 'name' => 'Customer'],
            ['type' => 'App\Models\Booking', 'name' => 'Booking'],
            ['type' => 'App\Models\Invoice', 'name' => 'Invoice'],
            ['type' => 'App\Models\Payment', 'name' => 'Payment'],
            ['type' => 'App\Models\Lead', 'name' => 'Lead'],
            ['type' => 'App\Models\User', 'name' => 'User'],
        ];
        
        $logs = [];
        for ($i = 0; $i < 15; $i++) {
            $model = $models[array_rand($models)];
            $logs[] = [
                'user_id' => $users->random()->id,
                'action' => $actions[array_rand($actions)],
                'model_type' => $model['type'],
                'model_id' => rand(1, 100),
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'old_values' => json_encode(['status' => 'pending']),
                'new_values' => json_encode(['status' => 'approved']),
                'description' => $model['name'] . ' record was updated',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ];
        }

        foreach ($logs as $logData) {
            AuditLog::create($logData);
        }

        $this->command->info('AuditLogSeeder: Created ' . count($logs) . ' audit logs!');
    }
}
