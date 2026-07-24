@extends('layouts.admin')
@section('title', 'New Flight Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-airplane"></i> New Flight Request</h1>
    <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.flights.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->user->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trip Type *</label>
                    <select name="trip_type" class="form-select" required>
                        <option value="oneway">One Way</option>
                        <option value="roundtrip">Round Trip</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">From (Airport)</label>
                    <select name="from_airport_id" class="form-select">
                        <option value="">Select Airport</option>
                        @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }} ({{ $airport->iata_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">To (Airport)</label>
                    <select name="to_airport_id" class="form-select">
                        <option value="">Select Airport</option>
                        @foreach($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->name }} ({{ $airport->iata_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Departure Date</label>
                    <input type="date" name="departure_date" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Adults</label>
                    <input type="number" name="adults" class="form-control" value="1" min="1" max="9">
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Create Request
            </button>
        </form>
    </div>
</div>
@endsection
