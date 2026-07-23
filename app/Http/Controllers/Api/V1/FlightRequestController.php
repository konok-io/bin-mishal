<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\FlightRequest;
use App\Models\FlightQuote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightRequestController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = FlightRequest::with(['customer.user', 'fromAirport', 'toAirport', 'preferredAirline', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('trip_type')) {
            $query->where('trip_type', $request->trip_type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        $requests = $query->latest()->paginate($this->perPage($request));

        return $this->paginate($requests);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'trip_type' => 'required|in:oneway,roundtrip,multicity',
            'from_airport_id' => 'nullable|exists:airports,id',
            'to_airport_id' => 'nullable|exists:airports,id',
            'departure_date' => 'nullable|date|after_or_equal:today',
            'return_date' => 'nullable|date|after:departure_date',
            'adults' => 'nullable|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'cabin_class' => 'nullable|in:economy,premium,business,first',
            'preferred_airline_id' => 'nullable|exists:airlines,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:budget_min',
            'baggage_requirement' => 'nullable|string|max:255',
            'special_request' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $request_data = $request->except('special_request');
            $request_data['request_no'] = FlightRequest::generateNo();
            $request_data['status'] = 'pending';

            if ($request->has('special_request')) {
                $request_data['special_request'] = $request->special_request;
            }

            $flightRequest = FlightRequest::create($request_data);

            DB::commit();

            return $this->success(
                $flightRequest->load('customer.user', 'fromAirport', 'toAirport'),
                'Flight request created successfully',
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create flight request: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $flightRequest = FlightRequest::with([
            'customer.user',
            'fromAirport',
            'toAirport',
            'preferredAirline',
            'assignedTo',
            'quotes'
        ])->findOrFail($id);

        return $this->success($flightRequest);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $flightRequest = FlightRequest::findOrFail($id);

        $request->validate([
            'trip_type' => 'nullable|in:oneway,roundtrip,multicity',
            'from_airport_id' => 'nullable|exists:airports,id',
            'to_airport_id' => 'nullable|exists:airports,id',
            'departure_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'adults' => 'nullable|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'cabin_class' => 'nullable|in:economy,premium,business,first',
            'preferred_airline_id' => 'nullable|exists:airlines,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric',
            'baggage_requirement' => 'nullable|string|max:255',
            'special_request' => 'nullable|string',
        ]);

        $flightRequest->update($request->only([
            'trip_type', 'from_airport_id', 'to_airport_id', 'departure_date',
            'return_date', 'adults', 'children', 'infants', 'cabin_class',
            'preferred_airline_id', 'budget_min', 'budget_max',
            'baggage_requirement', 'special_request'
        ]));

        return $this->success($flightRequest->load('customer.user'), 'Flight request updated successfully');
    }

    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $flightRequest = FlightRequest::findOrFail($id);
        $flightRequest->update(['assigned_to' => $request->assigned_to]);

        return $this->success($flightRequest->load('assignedTo'), 'Request assigned successfully');
    }

    public function addQuote(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'airline_id' => 'nullable|exists:airlines,id',
            'flight_no' => 'nullable|string|max:20',
            'departure_datetime' => 'nullable|date',
            'arrival_datetime' => 'nullable|date',
            'stops' => 'nullable|integer|min:0|max:5',
            'layover_details' => 'nullable|array',
            'baggage_allowance' => 'nullable|string|max:255',
            'base_fare' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'service_charge' => 'nullable|numeric|min:0',
            'total_fare' => 'required|numeric|min:0',
            'valid_until' => 'nullable|date|after:today',
        ]);

        $flightRequest = FlightRequest::findOrFail($id);

        $quote = $flightRequest->quotes()->create([
            'airline_id' => $request->airline_id,
            'flight_no' => $request->flight_no,
            'departure_datetime' => $request->departure_datetime,
            'arrival_datetime' => $request->arrival_datetime,
            'stops' => $request->stops ?? 0,
            'layover_details' => $request->layover_details,
            'baggage_allowance' => $request->baggage_allowance,
            'base_fare' => $request->base_fare,
            'tax' => $request->tax ?? 0,
            'service_charge' => $request->service_charge ?? 0,
            'total_fare' => $request->total_fare,
            'valid_until' => $request->valid_until,
            'status' => 'sent',
        ]);

        return $this->success($quote->load('airline'), 'Quote added successfully', 201);
    }

    public function quotes(int $id): JsonResponse
    {
        $flightRequest = FlightRequest::findOrFail($id);
        $quotes = $flightRequest->quotes()->with('airline')->get();

        return $this->success($quotes);
    }

    public function cancel(int $id): JsonResponse
    {
        $flightRequest = FlightRequest::findOrFail($id);
        $flightRequest->update(['status' => 'cancelled']);

        return $this->success($flightRequest, 'Request cancelled');
    }
}
