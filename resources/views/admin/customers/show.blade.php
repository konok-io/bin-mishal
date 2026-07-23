@extends('layouts.admin')
@section('title', 'Customer Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> Customer: {{ $customer->customer_code }}</h1>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $customer->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $customer->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $customer->user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>WhatsApp:</th>
                        <td>{{ $customer->user->whatsapp ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Company:</th>
                        <td>{{ $customer->company_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Source:</th>
                        <td>{{ $customer->source ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned To:</th>
                        <td>{{ $customer->assignedTo->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Bookings</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Booking No</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->bookings as $booking)
                        <tr>
                            <td><a href="{{ route('admin.bookings.show', $booking->id) }}">{{ $booking->booking_no }}</a></td>
                            <td>{{ ucfirst($booking->booking_type) }}</td>
                            <td>SAR {{ number_format($booking->total_amount, 2) }}</td>
                            <td><span class="badge bg-{{ $booking->booking_status === 'issued' ? 'success' : 'warning' }}">{{ ucfirst($booking->booking_status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No bookings</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Visa Applications</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Application No</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->visaApplications as $visa)
                        <tr>
                            <td><a href="{{ route('admin.visas.show', $visa->id) }}">{{ $visa->application_no }}</a></td>
                            <td>{{ $visa->visaType->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-{{ $visa->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($visa->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No visa applications</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
