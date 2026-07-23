@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Customers</h6>
                        <h3 class="mb-0">{{ number_format($stats['customers']['total']) }}</h3>
                        <small class="text-success">+{{ $stats['customers']['new_this_month'] }} this month</small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Bookings</h6>
                        <h3 class="mb-0">{{ number_format($stats['bookings']['total']) }}</h3>
                        <small class="text-muted">{{ $stats['bookings']['pending'] }} pending</small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-ticket-perforated fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Visa Applications</h6>
                        <h3 class="mb-0">{{ number_format($stats['visas']['total']) }}</h3>
                        <small class="text-warning">{{ $stats['visas']['pending'] }} pending</small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-passport fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Revenue</h6>
                        <h3 class="mb-0">SAR {{ number_format($stats['payments']['total_received'], 2) }}</h3>
                        <small class="text-muted">SAR {{ number_format($stats['bookings']['total_revenue'], 2) }} from bookings</small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leads Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">New Leads</h6>
                <h3>{{ $stats['leads']['new'] }}</h3>
                <small class="text-muted">{{ $stats['leads']['due_today'] }} due today</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Converted Leads</h6>
                <h3>{{ $stats['leads']['converted'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Issued Bookings</h6>
                <h3>{{ $stats['bookings']['issued'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Bookings</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Booking No</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr>
                                <td><a href="{{ route('admin.bookings.show', $booking->id) }}">{{ $booking->booking_no }}</a></td>
                                <td>{{ $booking->customer->user->name ?? 'N/A' }}</td>
                                <td>SAR {{ number_format($booking->total_amount, 2) }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($booking->booking_status) {
                                            'pending' => 'bg-warning',
                                            'issued' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($booking->booking_status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No bookings yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Recent Payments</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Payment No</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td><a href="{{ route('admin.payments.show', $payment->id) }}">{{ $payment->payment_no }}</a></td>
                                <td>{{ $payment->customer->user->name ?? 'N/A' }}</td>
                                <td>SAR {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->paid_at?->format('d M Y') ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No payments yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
