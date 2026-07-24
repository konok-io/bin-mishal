<?php

namespace Database\Seeders;

use App\Models\CityTVConnect;
use Illuminate\Database\Seeder;

class CityTVConnectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'ঢাকা প্রধান অফিস - City TV Connect',
                'serial_number' => 'CTV-DHAKA-MAIN-001',
                'password' => 'Dhaka@2024',
                'ip_address' => '192.168.1.201',
                'port' => 8000,
                'status' => 'active',
                'notes' => 'প্রধান কার্যালয় - সকল ক্যামেরা সংযুক্ত',
            ],
            [
                'name' => 'চট্টগ্রাম শাখা - City TV Connect',
                'serial_number' => 'CTV-CHATTOGRAM-001',
                'password' => 'Chattogram@2024',
                'ip_address' => '192.168.2.201',
                'port' => 8000,
                'status' => 'active',
                'notes' => 'চট্টগ্রাম কার্যালয় - সকল ক্যামেরা সংযুক্ত',
            ],
            [
                'name' => 'সিলেট শাখা - City TV Connect',
                'serial_number' => 'CTV-SYLHET-001',
                'password' => 'Sylhet@2024',
                'ip_address' => '192.168.3.201',
                'port' => 8000,
                'status' => 'active',
                'notes' => 'সিলেট কার্যালয় - সকল ক্যামেরা সংযুক্ত',
            ],
            [
                'name' => 'রাজশাহী শাখা - City TV Connect',
                'serial_number' => 'CTV-RAJSHAHI-001',
                'password' => 'Rajshahi@2024',
                'ip_address' => '192.168.4.201',
                'port' => 8000,
                'status' => 'active',
                'notes' => 'রাজশাহী কার্যালয় - সকল ক্যামেরা সংযুক্ত',
            ],
            [
                'name' => 'খুলনা শাখা - City TV Connect',
                'serial_number' => 'CTV-KHULNA-001',
                'password' => 'Khulna@2024',
                'ip_address' => '192.168.5.201',
                'port' => 8000,
                'status' => 'active',
                'notes' => 'খুলনা কার্যালয় - সকল ক্যামেরা সংযুক্ত',
            ],
        ];

        foreach ($branches as $branch) {
            CityTVConnect::updateOrCreate(
                ['serial_number' => $branch['serial_number']],
                $branch
            );
        }

        $this->command->info('City TV Connect branches seeded successfully!');
    }
}
