@extends('layouts.admin')
@section('title', 'New Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-plus-circle"></i> New Booking</h1>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->user->name ?? 'N/A' }} ({{ $customer->customer_code }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Booking Type *</label>
                    <select name="booking_type" class="form-select @error('booking_type') is-invalid @enderror" required>
                        <option value="ticket" {{ old('booking_type') == 'ticket' ? 'selected' : '' }}>Ticket</option>
                        <option value="umrah" {{ old('booking_type') == 'umrah' ? 'selected' : '' }}>Umrah</option>
                        <option value="visa" {{ old('booking_type') == 'visa' ? 'selected' : '' }}>Visa</option>
                        <option value="package" {{ old('booking_type') == 'package' ? 'selected' : '' }}>Package</option>
                    </select>
                    @error('booking_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Amount (SAR) *</label>
                    <input type="number" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" 
                           value="{{ old('total_amount') }}" step="0.01" required>
                    @error('total_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Create Booking
            </button>
        </form>
    </div>
</div>
@endsection
