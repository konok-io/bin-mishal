@extends('admin.layouts.app')

@section('title', 'Cargo Bookings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cargo Bookings</h1>
        <div>
            <a href="{{ route('admin.cargo.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Booking
            </a>
            <a href="{{ route('admin.cargo.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Tracking #, name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Type</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cargos as $cargo)
                        <tr>
                            <td><strong>{{ $cargo->tracking_number }}</strong></td>
                            <td>
                                <div>{{ $cargo->sender_name }}</div>
                                <small class="text-muted">{{ $cargo->sender_city }}</small>
                            </td>
                            <td>
                                <div>{{ $cargo->receiver_name }}</div>
                                <small class="text-muted">{{ $cargo->receiver_city }}</small>
                            </td>
                            <td>{{ $cargo->cargoType?->name ?? '-' }}</td>
                            <td>{{ $cargo->weight }} kg</td>
                            <td>
                                @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'collected' => 'primary',
                                    'warehouse' => 'secondary',
                                    'in_transit' => 'primary',
                                    'customs' => 'info',
                                    'bd_hub' => 'info',
                                    'out_for_delivery' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    'returned' => 'dark',
                                ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$cargo->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $cargo->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $cargo->payment_status == 'paid' ? 'success' : ($cargo->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($cargo->payment_status) }}
                                </span>
                            </td>
                            <td>SAR {{ number_format($cargo->total_amount, 2) }}</td>
                            <td>{{ $cargo->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.cargo.show', $cargo->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.cargo.invoice', $cargo->id) }}" class="btn btn-sm btn-success" title="Invoice" target="_blank">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No bookings found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $cargos->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
