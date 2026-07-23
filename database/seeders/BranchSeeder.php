<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Bin Mishal Travel - Al Hufuf',
                'name_bn' => 'বিন মিশাল ট্রাভেল - আল হুফুফ',
                'name_ar' => 'بن ميثال للسفر - الهفوف',
                'code' => 'HOF',
                'city' => 'Al Hufuf',
                'address' => 'King Abdullah Road, Al Hufuf, Eastern Province, Saudi Arabia',
                'phone' => '+9661351234567',
                'email' => 'hof@binmishal.com',
                'whatsapp' => '+966500000100',
                'latitude' => 25.3688,
                'longitude' => 49.5882,
                'is_main' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Bin Mishal Travel - Dammam',
                'name_bn' => 'বিন মিশাল ট্রাভেল - দাম্মাম',
                'name_ar' => 'بن ميثال للسفر - الدمام',
                'code' => 'DMM',
                'city' => 'Dammam',
                'address' => 'Prince Muhammad Bin Abdulaziz Road, Dammam, Saudi Arabia',
                'phone' => '+9661381234567',
                'email' => 'dmm@binmishal.com',
                'whatsapp' => '+966500000101',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
                'is_main' => false,
                'status' => 'active',
            ],
            [
                'name' => 'Bin Mishal Travel - Riyadh',
                'name_bn' => 'বিন মিশাল ট্রাভেল - রিয়াদ',
                'name_ar' => 'بن ميثال للسفر - الرياض',
                'code' => 'RUH',
                'city' => 'Riyadh',
                'address' => 'King Fahd Road, Riyadh, Saudi Arabia',
                'phone' => '+9661121234567',
                'email' => 'ruh@binmishal.com',
                'whatsapp' => '+966500000102',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'is_main' => false,
                'status' => 'active',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['code' => $branch['code']], $branch);
        }

        $this->command->info('Branches created successfully!');
    }
}
