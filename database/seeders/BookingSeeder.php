<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::take(3)->get();
        $users = User::take(2)->get();

        $bookings = [
            [
                'booking_no' => 'BK-240720001',
                'pnr' => 'ABC123',
                'customer_id' => $customers->first()?->id,
                'booking_type' => BookingType::FLIGHT,
                'passenger_count' => 2,
                'total_amount' => 4500.00,
                'paid_amount' => 4500.00,
                'due_amount' => 0.00,
                'payment_status' => PaymentStatus::PAID,
                'booking_status' => BookingStatus::ISSUED,
                'issue_date' => now()->subDays(5),
            ],
            [
                'booking_no' => 'BK-240720002',
                'pnr' => 'DEF456',
                'customer_id' => $customers->skip(1)->first()?->id,
                'booking_type' => BookingType::VISA,
                'passenger_count' => 1,
                'total_amount' => 2500.00,
                'paid_amount' => 1000.00,
                'due_amount' => 1500.00,
                'payment_status' => PaymentStatus::PARTIAL,
                'booking_status' => BookingStatus::CONFIRMED,
                'issue_date' => now()->subDays(2),
            ],
            [
                'booking_no' => 'BK-240720003',
                'pnr' => 'GHI789',
                'customer_id' => $customers->last()?->id,
                'booking_type' => BookingType::FLIGHT,
                'passenger_count' => 4,
                'total_amount' => 12000.00,
                'paid_amount' => 0.00,
                'due_amount' => 12000.00,
                'payment_status' => PaymentStatus::UNPAID,
                'booking_status' => BookingStatus::PENDING,
            ],
            [
                'booking_no' => 'BK-240720004',
                'pnr' => 'JKL012',
                'customer_id' => $customers->first()?->id,
                'booking_type' => BookingType::UMRAH,
                'passenger_count' => 3,
                'total_amount' => 18000.00,
                'paid_amount' => 18000.00,
                'due_amount' => 0.00,
                'payment_status' => PaymentStatus::PAID,
                'booking_status' => BookingStatus::ISSUED,
                'issued_by' => $users->first()?->id,
                'issue_date' => now()->subDays(10),
            ],
            [
                'booking_no' => 'BK-240720005',
                'pnr' => 'MNO345',
                'customer_id' => $customers->skip(1)->first()?->id,
                'booking_type' => BookingType::FLIGHT,
                'passenger_count' => 1,
                'total_amount' => 2200.00,
                'paid_amount' => 2200.00,
                'due_amount' => 0.00,
                'payment_status' => PaymentStatus::PAID,
                'booking_status' => BookingStatus::CANCELLED,
                'cancellation_reason' => 'Customer requested cancellation',
            ],
        ];

        foreach ($bookings as $bookingData) {
            Booking::updateOrCreate(
                ['booking_no' => $bookingData['booking_no']],
                $bookingData
            );
        }

        $this->command->info('BookingSeeder: Created ' . count($bookings) . ' sample bookings.');
    }
}
