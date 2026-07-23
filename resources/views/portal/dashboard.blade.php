@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Welcome, {{ auth()->user()->name }}!</h2>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-airplane text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $stats['total_bookings'] ?? 0 }}</h4>
                            <small class="text-muted">Total Bookings</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">SAR {{ number_format($stats['total_paid'] ?? 0, 0) }}</h4>
                            <small class="text-muted">Total Paid</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-text text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $stats['pending_visas'] ?? 0 }}</h4>
                            <small class="text-muted">Pending Visas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-check text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $stats['upcoming_appointments'] ?? 0 }}</h4>
                            <small class="text-muted">Appointments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body p-0">
                    @if(empty($recentBookings))
                        <p class="text-muted p-4 mb-0">No bookings yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Booking #</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                    <tr>
                                        <td>{{ $booking['booking_no'] }}</td>
                                        <td>{{ ucfirst($booking['type']) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking['status'] === 'issued' ? 'success' : 'warning' }}">
                                                {{ ucfirst($booking['status']) }}
                                            </span>
                                        </td>
                                        <td>SAR {{ number_format($booking['total'], 2) }}</td>
                                        <td>
                                            <a href="{{ route('portal.bookings.show', $booking['id']) }}" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('portal.bookings.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-airplane me-2"></i> My Bookings
                        </a>
                        <a href="{{ route('portal.visas.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-file-text me-2"></i> My Visas
                        </a>
                        <a href="{{ route('portal.appointments.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-plus me-2"></i> Book Appointment
                        </a>
                        <a href="{{ route('portal.documents.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-upload me-2"></i> Upload Document
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
