<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            // Saudi Arabia
            ['name' => 'King Fahd International Airport', 'city' => 'Dammam', 'city_bn' => 'দাম্মাম', 'iata_code' => 'DMM', 'timezone' => 'Asia/Riyadh', 'country' => 'Saudi Arabia'],
            ['name' => 'King Abdulaziz International Airport', 'city' => 'Jeddah', 'city_bn' => 'জেদ্দা', 'iata_code' => 'JED', 'timezone' => 'Asia/Riyadh', 'country' => 'Saudi Arabia'],
            ['name' => 'King Khalid International Airport', 'city' => 'Riyadh', 'city_bn' => 'রিয়াদ', 'iata_code' => 'RUH', 'timezone' => 'Asia/Riyadh', 'country' => 'Saudi Arabia'],
            ['name' => 'Prince Mohammad bin Abdulaziz Airport', 'city' => 'Madinah', 'city_bn' => 'মদিনা', 'iata_code' => 'MED', 'timezone' => 'Asia/Riyadh', 'country' => 'Saudi Arabia'],
            ['name' => 'Al Ahsa Airport', 'city' => 'Al Hufuf', 'city_bn' => 'আল হুফুফ', 'iata_code' => 'HOF', 'timezone' => 'Asia/Riyadh', 'country' => 'Saudi Arabia'],

            // Bangladesh
            ['name' => 'Hazrat Shahjalal International Airport', 'city' => 'Dhaka', 'city_bn' => 'ঢাকা', 'iata_code' => 'DAC', 'timezone' => 'Asia/Dhaka', 'country' => 'Bangladesh'],
            ['name' => 'Shah Amanat International Airport', 'city' => 'Chittagong', 'city_bn' => 'চট্টগ্রাম', 'iata_code' => 'CGP', 'timezone' => 'Asia/Dhaka', 'country' => 'Bangladesh'],
            ['name' => 'Osmani International Airport', 'city' => 'Sylhet', 'city_bn' => 'সিলেট', 'iata_code' => 'ZYL', 'timezone' => 'Asia/Dhaka', 'country' => 'Bangladesh'],

            // India
            ['name' => 'Indira Gandhi International Airport', 'city' => 'Delhi', 'city_bn' => 'দিল্লি', 'iata_code' => 'DEL', 'timezone' => 'Asia/Kolkata', 'country' => 'India'],
            ['name' => 'Chhatrapati Shivaji Maharaj International Airport', 'city' => 'Mumbai', 'city_bn' => 'মুম্বাই', 'iata_code' => 'BOM', 'timezone' => 'Asia/Kolkata', 'country' => 'India'],

            // Pakistan
            ['name' => 'Allama Iqbal International Airport', 'city' => 'Lahore', 'city_bn' => 'লাহোর', 'iata_code' => 'LHE', 'timezone' => 'Asia/Karachi', 'country' => 'Pakistan'],
            ['name' => 'Jinnah International Airport', 'city' => 'Karachi', 'city_bn' => 'করাচি', 'iata_code' => 'KHI', 'timezone' => 'Asia/Karachi', 'country' => 'Pakistan'],

            // Nepal
            ['name' => 'Tribhuvan International Airport', 'city' => 'Kathmandu', 'city_bn' => 'কাঠমান্ডু', 'iata_code' => 'KTM', 'timezone' => 'Asia/Kathmandu', 'country' => 'Nepal'],

            // Sri Lanka
            ['name' => 'Bandaranaike International Airport', 'city' => 'Colombo', 'city_bn' => 'কলম্বো', 'iata_code' => 'CMB', 'timezone' => 'Asia/Colombo', 'country' => 'Sri Lanka'],

            // UAE
            ['name' => 'Dubai International Airport', 'city' => 'Dubai', 'city_bn' => 'দুবাই', 'iata_code' => 'DXB', 'timezone' => 'Asia/Dubai', 'country' => 'UAE'],
            ['name' => 'Abu Dhabi International Airport', 'city' => 'Abu Dhabi', 'city_bn' => 'আবু ধাবি', 'iata_code' => 'AUH', 'timezone' => 'Asia/Dubai', 'country' => 'UAE'],

            // Qatar
            ['name' => 'Hamad International Airport', 'city' => 'Doha', 'city_bn' => 'দোহা', 'iata_code' => 'DOH', 'timezone' => 'Asia/Qatar', 'country' => 'Qatar'],
        ];

        foreach ($airports as $airport) {
            Airport::firstOrCreate(['iata_code' => $airport['iata_code']], $airport);
        }

        $this->command->info('Airports created successfully!');
    }
}
