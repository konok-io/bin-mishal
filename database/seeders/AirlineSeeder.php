<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'Saudi Arabian Airlines', 'name_bn' => 'সৌদি এরাবিয়ান এয়ারলাইন্স', 'name_ar' => 'الخطوط الجوية العربية السعودية', 'iata_code' => 'SV', 'icao_code' => 'SVA', 'country' => 'Saudi Arabia'],
            ['name' => 'Flynas', 'name_bn' => 'ফ্লাইনাস', 'name_ar' => 'طيران ناس', 'iata_code' => 'XY', 'icao_code' => 'KNE', 'country' => 'Saudi Arabia'],
            ['name' => 'Saudi Gulf Airlines', 'name_bn' => 'সৌদি গালফ এয়ারলাইন্স', 'name_ar' => 'الخطوط السعودية الخليجية', 'iata_code' => '6S', 'icao_code' => 'GFA', 'country' => 'Saudi Arabia'],
            ['name' => 'Biman Bangladesh Airlines', 'name_bn' => 'বিমান বাংলাদেশ এয়ারলাইন্স', 'name_ar' => 'خطوط بيمانغلاديش الجوية', 'iata_code' => 'BG', 'icao_code' => 'BBC', 'country' => 'Bangladesh'],
            ['name' => 'US-Bangla Airlines', 'name_bn' => 'ইউএস-বাংলা এয়ারলাইন্স', 'name_ar' => 'يوز بانغلاديش airlines', 'iata_code' => 'BS', 'icao_code' => 'UBG', 'country' => 'Bangladesh'],
            ['name' => 'Air Arabia', 'name_bn' => 'এয়ার আরাবিয়া', 'name_ar' => 'طيران العربية', 'iata_code' => 'G9', 'icao_code' => 'ABR', 'country' => 'UAE'],
            ['name' => 'Emirates', 'name_bn' => 'এমিরেটস', 'name_ar' => 'الإمارات', 'iata_code' => 'EK', 'icao_code' => 'UAE', 'country' => 'UAE'],
            ['name' => 'Qatar Airways', 'name_bn' => 'কাতার এয়ারওয়েজ', 'name_ar' => 'الخطوط القطرية', 'iata_code' => 'QR', 'icao_code' => 'QTR', 'country' => 'Qatar'],
            ['name' => 'Oman Air', 'name_bn' => 'ওমান এয়ার', 'name_ar' => 'الطيران العماني', 'iata_code' => 'WY', 'icao_code' => 'OMA', 'country' => 'Oman'],
            ['name' => 'SriLankan Airlines', 'name_bn' => 'শ্রীলঙ্কান এয়ারলাইন্স', 'name_ar' => 'الخطوط الجوية السيريلانكية', 'iata_code' => 'UL', 'icao_code' => 'ALK', 'country' => 'Sri Lanka'],
        ];

        foreach ($airlines as $airline) {
            Airline::firstOrCreate(['iata_code' => $airline['iata_code']], $airline);
        }

        $this->command->info('Airlines created successfully!');
    }
}
