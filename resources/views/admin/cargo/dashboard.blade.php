@extends('admin.layouts.app')

@section('title', 'Cargo Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cargo Management Dashboard</h1>
        <a href="{{ route('admin.cargo.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Booking
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Transit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_transit'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Delivered</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['delivered'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Today's Bookings</div>
                    <div class="h4 mb-0 text-gray-800">{{ $stats['today'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">This Month</div>
                    <div class="h4 mb-0 text-gray-800">{{ $stats['this_month'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue</div>
                    <div class="h4 mb-0 text-gray-800">SAR {{ number_format($stats['revenue'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending Payment</div>
                    <div class="h4 mb-0 text-gray-800">SAR {{ number_format($stats['pending_payment'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
            <a href="{{ route('admin.cargo.index') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCargos as $cargo)
                        <tr>
                            <td><strong>{{ $cargo->tracking_number }}</strong></td>
                            <td>{{ $cargo->sender_name }}</td>
                            <td>{{ $cargo->receiver_name }}</td>
                            <td>
                                <span class="badge bg-{{ $cargo->status == 'delivered' ? 'success' : ($cargo->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $cargo->status)) }}
                                </span>
                            </td>
                            <td>SAR {{ number_format($cargo->total_amount, 2) }}</td>
                            <td>{{ $cargo->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.cargo.show', $cargo->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No bookings yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.cargo.packages') }}" class="card shadow h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-box-open fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Package Types</h5>
                    <p class="text-muted small">Manage cargo packages</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.cargo.cities') }}" class="card shadow h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-map-marked-alt fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Cities & Zones</h5>
                    <p class="text-muted small">Manage delivery areas</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.cargo.pricing') }}" class="card shadow h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-tags fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Pricing</h5>
                    <p class="text-muted small">Set weight/package rates</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.cargo.coupons') }}" class="card shadow h-100 text-decoration-none">
                <div class="card-body text-center">
                    <i class="fas fa-ticket-alt fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Coupons</h5>
                    <p class="text-muted small">Manage discount codes</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
