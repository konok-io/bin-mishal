<?php

namespace App\Http\Controllers\Admin;

use App\Models\FlightRequest;
use App\Models\Customer;
use App\Models\Airport;
use App\Models\Airline;
use Illuminate\Http\Request;

class FlightRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = FlightRequest::with(['customer.user', 'fromAirport', 'toAirport']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);
        return view('admin.flights.index', compact('requests'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $airports = Airport::active()->get();
        $airlines = Airline::active()->get();
        return view('admin.flights.create', compact('customers', 'airports', 'airlines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'trip_type' => 'required|in:oneway,roundtrip,multicity',
            'from_airport_id' => 'nullable|exists:airports,id',
            'to_airport_id' => 'nullable|exists:airports,id',
            'departure_date' => 'nullable|date|after_or_equal:today',
            'adults' => 'nullable|integer|min:1|max:9',
        ]);

        FlightRequest::create([
            'request_no' => FlightRequest::generateNo(),
            'customer_id' => $request->customer_id,
            'trip_type' => $request->trip_type,
            'from_airport_id' => $request->from_airport_id,
            'to_airport_id' => $request->to_airport_id,
            'departure_date' => $request->departure_date,
            'adults' => $request->adults ?? 1,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.flights.index')->with('success', 'Flight request created');
    }

    public function show(int $id)
    {
        $request = FlightRequest::with([
            'customer.user',
            'fromAirport',
            'toAirport',
            'preferredAirline',
            'assignedTo',
            'quotes'
        ])->findOrFail($id);

        return view('admin.flights.show', compact('request'));
    }
}
