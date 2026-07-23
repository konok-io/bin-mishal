@extends('layouts.admin')
@section('title', 'Booking Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-ticket"></i> Booking: {{ $booking->booking_no }}</h1>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Booking Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="30%">Booking No:</th>
                        <td><strong>{{ $booking->booking_no }}</strong></td>
                    </tr>
                    <tr>
                        <th>Customer:</th>
                        <td>{{ $booking->customer->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><span class="badge bg-secondary">{{ ucfirst($booking->booking_type) }}</span></td>
                    </tr>
                    <tr>
                        <th>Passengers:</th>
                        <td>{{ $booking->passenger_count }}</td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td>SAR {{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Paid Amount:</th>
                        <td>SAR {{ number_format($booking->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Due Amount:</th>
                        <td>SAR {{ number_format($booking->due_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @php
                                $statusClass = match($booking->booking_status) {
                                    'pending' => 'bg-warning',
                                    'issued' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($booking->booking_status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Passengers -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Passengers</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Passport</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($booking->passengers as $passenger)
                        <tr>
                            <td>{{ $passenger->first_name }} {{ $passenger->last_name }}</td>
                            <td>{{ ucfirst($passenger->passenger_type ?? 'adult') }}</td>
                            <td>{{ $passenger->passport_no ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No passengers</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        @if($booking->booking_status === 'pending')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.bookings.issue', $booking->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Issue Booking
                    </button>
                </form>
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="text" name="reason" placeholder="Cancellation reason" class="form-control d-inline-block w-auto" required>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
