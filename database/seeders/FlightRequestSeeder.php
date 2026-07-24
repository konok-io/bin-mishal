<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\Customer;
use App\Models\FlightRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class FlightRequestSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::take(3)->get();
        $airports = Airport::take(4)->get();
        $users = User::take(2)->get();

        $requests = [
            [
                'request_no' => 'FR-240720001',
                'customer_id' => $customers->first()?->id,
                'trip_type' => 'roundtrip',
                'from_airport_id' => $airports->first()?->id,
                'to_airport_id' => $airports->skip(1)->first()?->id,
                'departure_date' => now()->addDays(45),
                'return_date' => now()->addDays(52),
                'adults' => 2,
                'children' => 1,
                'infants' => 0,
                'cabin_class' => 'economy',
                'budget_min' => 3000.00,
                'budget_max' => 5000.00,
                'baggage_requirement' => '23kg each',
                'special_request' => 'Window seats preferred',
                'status' => 'pending',
                'assigned_to' => $users->first()?->id,
            ],
            [
                'request_no' => 'FR-240720002',
                'customer_id' => $customers->skip(1)->first()?->id,
                'trip_type' => 'oneway',
                'from_airport_id' => $airports->skip(1)->first()?->id,
                'to_airport_id' => $airports->skip(2)->first()?->id,
                'departure_date' => now()->addDays(30),
                'return_date' => null,
                'adults' => 1,
                'children' => 0,
                'infants' => 0,
                'cabin_class' => 'business',
                'budget_min' => 8000.00,
                'budget_max' => 15000.00,
                'baggage_requirement' => '32kg',
                'special_request' => 'Vegetarian meal required',
                'status' => 'quoted',
                'assigned_to' => $users->skip(1)->first()?->id,
            ],
            [
                'request_no' => 'FR-240720003',
                'customer_id' => $customers->last()?->id,
                'trip_type' => 'roundtrip',
                'from_airport_id' => $airports->first()?->id,
                'to_airport_id' => $airports->last()?->id,
                'departure_date' => now()->addDays(60),
                'return_date' => now()->addDays(90),
                'adults' => 4,
                'children' => 2,
                'infants' => 1,
                'cabin_class' => 'economy',
                'budget_min' => 10000.00,
                'budget_max' => 18000.00,
                'baggage_requirement' => '30kg each + infant bag',
                'special_request' => 'Wheelchair assistance needed',
                'status' => 'pending',
                'assigned_to' => $users->first()?->id,
            ],
            [
                'request_no' => 'FR-240720004',
                'customer_id' => $customers->first()?->id,
                'trip_type' => 'multicity',
                'from_airport_id' => $airports->first()?->id,
                'to_airport_id' => $airports->skip(2)->first()?->id,
                'departure_date' => now()->addDays(21),
                'return_date' => now()->addDays(35),
                'adults' => 2,
                'children' => 0,
                'infants' => 0,
                'cabin_class' => 'first',
                'budget_min' => 25000.00,
                'budget_max' => 40000.00,
                'baggage_requirement' => '2 bags each',
                'special_request' => 'Honeymoon celebration',
                'status' => 'quoted',
                'assigned_to' => $users->skip(1)->first()?->id,
            ],
        ];

        foreach ($requests as $requestData) {
            FlightRequest::updateOrCreate(
                ['request_no' => $requestData['request_no']],
                $requestData
            );
        }

        $this->command->info('FlightRequestSeeder: Created ' . count($requests) . ' sample flight requests.');
    }
}
