@extends('layouts.admin')
@section('title', 'Flight Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-airplane"></i> Request: {{ $request->request_no }}</h1>
    <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Request Details</h5></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Customer:</th><td>{{ $request->customer->user->name ?? 'N/A' }}</td></tr>
                    <tr><th>Trip Type:</th><td>{{ ucfirst($request->trip_type) }}</td></tr>
                    <tr><th>Route:</th><td>{{ $request->fromAirport->name ?? 'N/A' }} → {{ $request->toAirport->name ?? 'N/A' }}</td></tr>
                    <tr><th>Departure:</th><td>{{ $request->departure_date?->format('d M Y') }}</td></tr>
                    <tr><th>Return:</th><td>{{ $request->return_date?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><th>Adults:</th><td>{{ $request->adults }}</td></tr>
                    <tr><th>Children:</th><td>{{ $request->children ?? 0 }}</td></tr>
                    <tr><th>Cabin:</th><td>{{ ucfirst($request->cabin_class ?? 'Economy') }}</td></tr>
                    <tr><th>Status:</th><td><span class="badge bg-warning">{{ ucfirst($request->status) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Quotes</h5></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Airline</th><th>Flight</th><th>Fare</th></tr></thead>
                    <tbody>
                        @forelse($request->quotes as $quote)
                        <tr>
                            <td>{{ $quote->airline->name ?? '-' }}</td>
                            <td>{{ $quote->flight_no ?? '-' }}</td>
                            <td>SAR {{ number_format($quote->total_fare, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No quotes yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
