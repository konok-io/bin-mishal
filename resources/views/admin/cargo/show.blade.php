@extends('admin.layouts.app')

@section('title', 'Cargo Details - ' . $cargo->tracking_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Cargo Details</h1>
            <span class="badge bg-primary fs-6">{{ $cargo->tracking_number }}</span>
        </div>
        <div>
            <a href="{{ route('admin.cargo.invoice', $cargo->id) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-file-invoice"></i> Invoice
            </a>
            <a href="{{ route('admin.cargo.label', $cargo->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-tag"></i> Label
            </a>
            <a href="{{ route('admin.cargo.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cargo Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Sender Information</h6>
                            <p class="mb-1"><strong>{{ $cargo->sender_name }}</strong></p>
                            <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $cargo->sender_phone }}</p>
                            @if($cargo->sender_email)
                            <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $cargo->sender_email }}</p>
                            @endif
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $cargo->sender_address }}, {{ $cargo->sender_city }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Receiver Information</h6>
                            <p class="mb-1"><strong>{{ $cargo->receiver_name }}</strong></p>
                            <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $cargo->receiver_phone }}</p>
                            @if($cargo->receiver_email)
                            <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $cargo->receiver_email }}</p>
                            @endif
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ $cargo->receiver_address }}, {{ $cargo->receiver_city }}</p>
                        </div>
                        <hr>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Cargo Type</h6>
                            <p class="mb-1">{{ $cargo->cargoType?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Package</h6>
                            <p class="mb-1">{{ $cargo->cargoPackage?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Weight</h6>
                            <p class="mb-1">{{ $cargo->weight }} kg</p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p>{{ $cargo->cargo_description ?? 'No description' }}</p>
                        </div>
                        @if($cargo->special_instructions)
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Special Instructions</h6>
                            <p class="text-warning">{{ $cargo->special_instructions }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pricing</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Shipping Cost</td>
                            <td class="text-end">SAR {{ number_format($cargo->shipping_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <td>VAT ({{ $cargo->vat_amount > 0 ? number_format(($cargo->vat_amount / $cargo->shipping_cost) * 100, 1) : 15 }}%)</td>
                            <td class="text-end">SAR {{ number_format($cargo->vat_amount, 2) }}</td>
                        </tr>
                        @if($cargo->discount_amount > 0)
                        <tr class="text-success">
                            <td>Discount</td>
                            <td class="text-end">-SAR {{ number_format($cargo->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="fw-bold fs-5">
                            <td>Total</td>
                            <td class="text-end">SAR {{ number_format($cargo->total_amount, 2) }}</td>
                        </tr>
                    </table>
                    <span class="badge bg-{{ $cargo->payment_status == 'paid' ? 'success' : ($cargo->payment_status == 'partial' ? 'warning' : 'danger') }}">
                        Payment {{ ucfirst($cargo->payment_status) }}
                    </span>
                </div>
            </div>

            <!-- Tracking History -->
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tracking History</h6>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-plus"></i> Update Status
                    </button>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($cargo->trackingHistory as $tracking)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-primary rounded-circle p-2">
                                        <i class="fas fa-circle"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $tracking->status }}</h6>
                                    <p class="mb-1 text-muted">{{ $tracking->description }}</p>
                                    @if($tracking->location)
                                    <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $tracking->location }}</small>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $tracking->timestamp->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">No tracking history</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Status</h6>
                </div>
                <div class="card-body text-center">
                    @php
                    $statusClass = match($cargo->status) {
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        'returned' => 'dark',
                        default => 'warning'
                    };
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusClass }} fs-5 px-4 py-2">
                            {{ ucfirst(str_replace('_', ' ', $cargo->status)) }}
                        </span>
                    </div>
                    <p class="text-muted mb-0">
                        <small>Created: {{ $cargo->created_at->format('d M Y, h:i A') }}</small>
                    </p>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Info</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Est. Delivery</td>
                            <td>{{ $cargo->estimated_delivery?->format('d M Y') ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Pickup Date</td>
                            <td>{{ $cargo->pickup_date?->format('d M Y') ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td>{{ $cargo->branch?->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Declared Value</td>
                            <td>SAR {{ number_format($cargo->declared_value, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cargo.status', $cargo->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="collected">Collected</option>
                            <option value="warehouse">Warehouse</option>
                            <option value="in_transit">In Transit</option>
                            <option value="customs">Customs</option>
                            <option value="bd_hub">BD Hub</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter status description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Enter location">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
