<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cargo\Cargo;
use App\Models\User;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(2)->get();

        $cargos = [
            [
                'tracking_number' => 'CRG-240720001',
                'customer_id' => $users->first()?->id,
                'sender_name' => 'Ali Hassan',
                'sender_phone' => '+966501234567',
                'sender_email' => 'ali.hassan@email.com',
                'sender_address' => '123 King Fahd Road, Riyadh',
                'sender_city' => 'Riyadh',
                'receiver_name' => 'Rahim Ahmed',
                'receiver_phone' => '+8801712345678',
                'receiver_email' => 'rahim.ahmed@email.com',
                'receiver_address' => '45 Gulshan Avenue, Dhaka',
                'receiver_city' => 'Dhaka',
                'cargo_type_id' => 1,
                'cargo_description' => 'Electronics - Mobile Phones',
                'quantity' => 10,
                'weight' => 5.5,
                'declared_value' => 5000.00,
                'shipping_cost' => 450.00,
                'vat_amount' => 67.50,
                'total_amount' => 517.50,
                'pickup_date' => now()->addDays(1),
                'estimated_delivery' => now()->addDays(7),
                'status' => Cargo::STATUS_PENDING,
                'payment_status' => Cargo::PAYMENT_UNPAID,
                'payment_method' => 'cod',
            ],
            [
                'tracking_number' => 'CRG-240720002',
                'customer_id' => $users->skip(1)->first()?->id,
                'sender_name' => 'Mohammed Ali',
                'sender_phone' => '+966557890123',
                'sender_email' => 'mohammed.ali@email.com',
                'sender_address' => '78 Olaya Street, Jeddah',
                'sender_city' => 'Jeddah',
                'receiver_name' => 'Karim Bhuiyan',
                'receiver_phone' => '+8801812345678',
                'receiver_email' => 'karim.bhuiyan@email.com',
                'receiver_address' => '12 Elephant Road, Dhaka',
                'receiver_city' => 'Dhaka',
                'cargo_type_id' => 2,
                'cargo_description' => 'Clothing - Business Suits',
                'quantity' => 20,
                'weight' => 8.0,
                'declared_value' => 8000.00,
                'shipping_cost' => 600.00,
                'vat_amount' => 90.00,
                'total_amount' => 690.00,
                'pickup_date' => now()->subDays(1),
                'estimated_delivery' => now()->addDays(5),
                'status' => Cargo::STATUS_IN_TRANSIT,
                'payment_status' => Cargo::PAYMENT_PAID,
                'payment_method' => 'bank_transfer',
            ],
            [
                'tracking_number' => 'CRG-240720003',
                'customer_id' => $users->first()?->id,
                'sender_name' => 'Fatima Trading',
                'sender_phone' => '+966541234567',
                'sender_email' => 'info@fatimatrading.com',
                'sender_address' => '456 Dammam Highway, Dammam',
                'sender_city' => 'Dammam',
                'receiver_name' => 'Sultan Brothers',
                'receiver_phone' => '+8801912345678',
                'receiver_email' => 'sultanbrothers@email.com',
                'receiver_address' => '78 Chittagong Port Area',
                'receiver_city' => 'Chittagong',
                'cargo_type_id' => 3,
                'cargo_description' => 'Auto Parts - Spare Components',
                'quantity' => 50,
                'weight' => 25.0,
                'declared_value' => 15000.00,
                'shipping_cost' => 1200.00,
                'vat_amount' => 180.00,
                'total_amount' => 1380.00,
                'pickup_date' => now()->subDays(3),
                'estimated_delivery' => now()->addDays(10),
                'status' => Cargo::STATUS_CONFIRMED,
                'payment_status' => Cargo::PAYMENT_PARTIAL,
                'payment_method' => 'online',
            ],
            [
                'tracking_number' => 'CRG-240720004',
                'customer_id' => $users->skip(1)->first()?->id,
                'sender_name' => 'Hassan Electronics',
                'sender_phone' => '+966531234567',
                'sender_email' => 'hassan.elec@email.com',
                'sender_address' => '321 Al-Masyaf, Riyadh',
                'sender_city' => 'Riyadh',
                'receiver_name' => 'Rahim Electronics',
                'receiver_phone' => '+8801712345679',
                'receiver_email' => 'rahim.elec@email.com',
                'receiver_address' => '99 Elephant Road, Dhaka',
                'receiver_city' => 'Dhaka',
                'cargo_type_id' => 1,
                'cargo_description' => 'Laptops and Accessories',
                'quantity' => 5,
                'weight' => 12.0,
                'declared_value' => 25000.00,
                'shipping_cost' => 900.00,
                'vat_amount' => 135.00,
                'total_amount' => 1035.00,
                'pickup_date' => now()->subDays(5),
                'estimated_delivery' => now()->addDays(3),
                'status' => Cargo::STATUS_DELIVERED,
                'payment_status' => Cargo::PAYMENT_PAID,
                'payment_method' => 'bank_transfer',
            ],
            [
                'tracking_number' => 'CRG-240720005',
                'customer_id' => $users->first()?->id,
                'sender_name' => 'Khan Families',
                'sender_phone' => '+966561234567',
                'sender_email' => 'khanfamily@email.com',
                'sender_address' => '555 Malaz District, Riyadh',
                'sender_city' => 'Riyadh',
                'receiver_name' => 'Family Reunion',
                'receiver_phone' => '+8801812345679',
                'receiver_email' => 'family.reunion@email.com',
                'receiver_address' => '22 Mirpur, Dhaka',
                'receiver_city' => 'Dhaka',
                'cargo_type_id' => 4,
                'cargo_description' => 'Household Items - Personal Effects',
                'quantity' => 15,
                'weight' => 35.0,
                'declared_value' => 5000.00,
                'shipping_cost' => 1500.00,
                'vat_amount' => 225.00,
                'total_amount' => 1725.00,
                'pickup_date' => now()->addDays(2),
                'estimated_delivery' => now()->addDays(14),
                'special_instructions' => 'Handle with care - fragile items included',
                'status' => Cargo::STATUS_PENDING,
                'payment_status' => Cargo::PAYMENT_UNPAID,
                'payment_method' => 'cod',
            ],
        ];

        foreach ($cargos as $cargoData) {
            Cargo::updateOrCreate(
                ['tracking_number' => $cargoData['tracking_number']],
                $cargoData
            );
        }

        $this->command->info('CargoSeeder: Created ' . count($cargos) . ' sample cargo shipments.');
    }
}
