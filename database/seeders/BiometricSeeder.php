<?php

namespace Database\Seeders;

use App\Models\BiometricDevice;
use Illuminate\Database\Seeder;

class BiometricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample device configurations for different brands
        $devices = [
            [
                'device_id' => 'BIO-MAIN-001',
                'name' => 'Main Entrance - Ground Floor',
                'brand' => 'zkteco',
                'model' => 'C3-400',
                'ip_address' => '192.168.1.101',
                'port' => 4370,
                'sync_method' => 'webhook',
                'webhook_url' => config('app.url') . '/api/biometric/webhook/BIO-MAIN-001',
                'status' => 'active',
                'sync_interval' => 5,
                'notes' => 'Primary entry point for all employees',
            ],
            [
                'device_id' => 'BIO-BACK-001',
                'name' => 'Back Entrance - Parking Area',
                'brand' => 'zkteco',
                'model' => 'C3-200',
                'ip_address' => '192.168.1.102',
                'port' => 4370,
                'sync_method' => 'webhook',
                'webhook_url' => config('app.url') . '/api/biometric/webhook/BIO-BACK-001',
                'status' => 'active',
                'sync_interval' => 5,
                'notes' => 'Secondary entrance for parking area access',
            ],
            [
                'device_id' => 'BIO-HIK-001',
                'name' => 'Hikvision DS-K1T671M',
                'brand' => 'hikvision',
                'model' => 'DS-K1T671M-3XF',
                'ip_address' => '192.168.1.103',
                'port' => 80,
                'sync_method' => 'webhook',
                'webhook_url' => config('app.url') . '/api/biometric/webhook/BIO-HIK-001',
                'status' => 'active',
                'sync_interval' => 5,
                'notes' => 'Face recognition device at reception',
            ],
            [
                'device_id' => 'BIO-MANUAL-001',
                'name' => 'Manual Attendance Station',
                'brand' => 'other',
                'sync_method' => 'manual',
                'status' => 'active',
                'notes' => 'For locations without network connectivity - use CSV export',
            ],
        ];

        foreach ($devices as $deviceData) {
            BiometricDevice::updateOrCreate(
                ['device_id' => $deviceData['device_id']],
                $deviceData
            );
        }

        $this->command->info('Biometric devices seeded successfully!');
    }
}
