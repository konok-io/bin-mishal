@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
{{-- Date Range Filter --}}
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <form method="GET" class="d-flex gap-2">
            <select name="date_range" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Today</option>
                <option value="7days" {{ $dateRange == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30days" {{ $dateRange == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="this_month" {{ $dateRange == 'this_month' ? 'selected' : '' }}>This Month</option>
            </select>
        </form>
    </div>
</div>

{{-- Pending Approvals Alert --}}
@if($totalPendingApprovals > 0)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle"></i> {{ $totalPendingApprovals }} pending items need attention:</strong>
    <ul class="mb-0 d-inline">
        @if($pendingApprovals['leave_requests'] > 0)
            <li class="d-inline me-3">{{ $pendingApprovals['leave_requests'] }} Leave Requests</li>
        @endif
        @if($pendingApprovals['expense_claims'] > 0)
            <li class="d-inline me-3">{{ $pendingApprovals['expense_claims'] }} Expense Claims</li>
        @endif
        @if($pendingApprovals['pending_comments'] > 0)
            <li class="d-inline me-3">{{ $pendingApprovals['pending_comments'] }} Comments</li>
        @endif
        @if($pendingApprovals['job_applications'] > 0)
            <li class="d-inline me-3">{{ $pendingApprovals['job_applications'] }} Job Applications</li>
        @endif
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Biometric Device Offline Alert --}}
@if($stats['biometric']['offline_devices'] > 0)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-wifi-off"></i> {{ $stats['biometric']['offline_devices'] }} Biometric Device(s) Offline</strong> - Check device connectivity
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Main KPI Cards --}}
<div class="row g-3 mb-4">
    {{-- Customers --}}
    <div class="col-md-6 col-lg-3">
        <div class="card border-primary h-100">
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
    
    {{-- Bookings --}}
    <div class="col-md-6 col-lg-3">
        <div class="card border-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Bookings</h6>
                        <h3 class="mb-0">{{ number_format($stats['bookings']['total']) }}</h3>
                        <small class="text-warning">{{ $stats['bookings']['pending'] }} pending</small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-ticket-perforated fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Cargo --}}
    <div class="col-md-6 col-lg-3">
        <div class="card border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Cargo Shipments</h6>
                        <h3 class="mb-0">{{ number_format($stats['cargo']['total']) }}</h3>
                        <small class="text-info">{{ $stats['cargo']['in_transit'] }} in transit</small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-box-seam fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Revenue --}}
    <div class="col-md-6 col-lg-3">
        <div class="card border-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Revenue</h6>
                        <h3 class="mb-0">SAR {{ number_format($stats['payments']['total_received'] + $stats['cargo']['total_revenue'], 0) }}</h3>
                        <small class="text-muted">Bookings + Cargo</small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Secondary Stats Row --}}
<div class="row g-3 mb-4">
    {{-- Visa Applications --}}
    <div class="col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-passport text-secondary fs-2"></i>
                <h4 class="mt-2 mb-0">{{ number_format($stats['visas']['total']) }}</h4>
                <small class="text-muted">Visa Applications</small>
                @if($stats['visas']['pending'] > 0)
                    <span class="badge bg-warning mt-1">{{ $stats['visas']['pending'] }} pending</span>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Investment Applications --}}
    <div class="col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-briefcase text-secondary fs-2"></i>
                <h4 class="mt-2 mb-0">{{ number_format($stats['investments']['total']) }}</h4>
                <small class="text-muted">Investments</small>
                @if($stats['investments']['pending'] > 0)
                    <span class="badge bg-warning mt-1">{{ $stats['investments']['pending'] }} pending</span>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Open Jobs --}}
    <div class="col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-briefcase-fill text-secondary fs-2"></i>
                <h4 class="mt-2 mb-0">{{ number_format($stats['careers']['open_positions']) }}</h4>
                <small class="text-muted">Open Positions</small>
                @if($stats['careers']['new_applications'] > 0)
                    <span class="badge bg-info mt-1">+{{ $stats['careers']['new_applications'] }} new</span>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Employees --}}
    <div class="col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-people-fill text-secondary fs-2"></i>
                <h4 class="mt-2 mb-0">{{ number_format($stats['hr']['active_employees']) }}</h4>
                <small class="text-muted">Active Employees</small>
                <div class="small mt-1">
                    <span class="text-success">{{ $stats['hr']['today_present'] }}</span> /
                    <span class="text-muted">{{ $stats['hr']['total_employees'] }}</span> today
                </div>
            </div>
        </div>
    </div>
    
    {{-- Unread Messages --}}
    <div class="col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-envelope text-secondary fs-2"></i>
                <h4 class="mt-2 mb-0">{{ number_format($stats['messages']['unread']) }}</h4>
                <small class="text-muted">Unread Messages</small>
                @if($stats['messages']['total'] > 0)
                    <span class="badge bg-secondary mt-1">{{ $stats['messages']['total'] }} total</span>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Newsletter Subscribers --}}
    <div class="col-md-4 col-lg-2">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-newspaper text-secondary fs-2"></i>
                <h4 class="mt-2 mb-0">{{ number_format($stats['content']['newsletter_subscribers']) }}</h4>
                <small class="text-muted">Subscribers</small>
                @if($stats['content']['new_subscribers'] > 0)
                    <span class="badge bg-success mt-1">+{{ $stats['content']['new_subscribers'] }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- HR/Accounting Summary Row --}}
<div class="row g-3 mb-4">
    {{-- Leads --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted"><i class="bi bi-person-plus me-1"></i>Leads</h6>
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ $stats['leads']['new'] }}</h3>
                        <small class="text-muted">New</small>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-0 text-success">{{ $stats['leads']['converted'] }}</h3>
                        <small class="text-muted">Converted</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Pending Expenses --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted"><i class="bi bi-receipt me-1"></i>Pending Expenses</h6>
                <h3 class="mb-0">{{ $stats['expenses']['pending_claims'] }}</h3>
                <small class="text-muted">SAR {{ number_format($stats['expenses']['total_claimed'], 0) }} total</small>
            </div>
        </div>
    </div>
    
    {{-- Income/Expense --}}
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-muted"><i class="bi bi-graph-up me-1"></i>Net Profit</h6>
                <h3 class="mb-0 {{ $stats['accounting']['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    SAR {{ number_format($stats['accounting']['net_profit'], 0) }}
                </h3>
                <small class="text-muted">
                    In: SAR {{ number_format($stats['accounting']['total_income'], 0) }} |
                    Out: SAR {{ number_format($stats['accounting']['total_expense'], 0) }}
                </small>
            </div>
        </div>
    </div>
    
    {{-- Pending Approvals --}}
    <div class="col-md-3">
        <div class="card h-100 border-warning">
            <div class="card-body">
                <h6 class="text-muted"><i class="bi bi-hourglass-split me-1"></i>Pending Approvals</h6>
                <h3 class="mb-0 text-warning">{{ $totalPendingApprovals }}</h3>
                <small class="text-muted">
                    {{ $pendingApprovals['leave_requests'] }} Leave |
                    {{ $pendingApprovals['expense_claims'] }} Expense
                </small>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity Row --}}
<div class="row">
    {{-- Recent Bookings --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Bookings</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
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
                                <td>SAR {{ number_format($booking->total_amount, 0) }}</td>
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
    
    {{-- Recent Messages --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Recent Messages</h5>
                @if($stats['messages']['unread'] > 0)
                    <span class="badge bg-warning">{{ $stats['messages']['unread'] }} unread</span>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMessages as $message)
                            <tr class="{{ !$message->is_read ? 'fw-bold' : '' }}">
                                <td>{{ $message->name }}</td>
                                <td>{{ Str::limit($message->subject ?? $message->message, 30) }}</td>
                                <td>{{ $message->created_at->format('d M') }}</td>
                                <td>
                                    @if(!$message->is_read)
                                        <span class="badge bg-warning">New</span>
                                    @else
                                        <span class="badge bg-secondary">Read</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No messages yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cargo & Payments Row --}}
<div class="row">
    {{-- Recent Cargo --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Recent Cargo Shipments</h5>
                <a href="{{ route('admin.cargo.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tracking No</th>
                                <th>Customer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCargo as $cargo)
                            <tr>
                                <td><a href="{{ route('admin.cargo.show', $cargo->id) }}">{{ $cargo->tracking_number }}</a></td>
                                <td>{{ $cargo->customer->user->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = match($cargo->status) {
                                            'booked', 'processing' => 'bg-info',
                                            'in_transit', 'customs' => 'bg-warning',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $cargo->status)) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No cargo shipments yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Recent Payments --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Recent Payments</h5>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
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
                                <td>SAR {{ number_format($payment->amount, 0) }}</td>
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

{{-- Top Services Row --}}
@if($topServices->isNotEmpty())
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-star me-2"></i>Top Services by Bookings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($topServices as $service)
                    <div class="col-md-2 col-6 text-center">
                        <div class="p-3 border rounded">
                            <h4 class="mb-0">{{ $service->count }}</h4>
                            <small class="text-muted text-capitalize">{{ str_replace('_', ' ', $service->service_type) }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
