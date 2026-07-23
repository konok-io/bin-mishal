@extends('public.layouts.master')

@section('title', 'Track Cargo - ' . $cargo->tracking_number)

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="mb-3">Cargo Tracking</h1>
        <p class="lead opacity-75">Tracking Number: <strong>{{ $cargo->tracking_number }}</strong></p>
    </div>
</section>

<!-- Tracking Result -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Tracking Timeline -->
            <div class="col-lg-8">
                <div class="card card-custom p-4">
                    <h4 class="mb-4"><i class="fas fa-route me-2 text-primary-custom"></i>Shipment Timeline</h4>
                    
                    <!-- Current Status -->
                    <div class="alert alert-{{ $cargo->status == 'delivered' ? 'success' : ($cargo->status == 'cancelled' ? 'danger' : 'info') }} mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-{{ $cargo->status == 'delivered' ? 'check-circle' : ($cargo->status == 'cancelled' ? 'times-circle' : 'info-circle') }} fa-2x me-3"></i>
                            <div>
                                <strong>Current Status: {{ ucwords(str_replace('_', ' ', $cargo->status)) }}</strong>
                                <p class="mb-0 small">Last updated: {{ $cargo->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="tracking-timeline">
                        @php
                        $statusOrder = [
                            'pending' => 1,
                            'confirmed' => 2,
                            'collected' => 3,
                            'warehouse' => 4,
                            'in_transit' => 5,
                            'customs' => 6,
                            'bd_hub' => 7,
                            'out_for_delivery' => 8,
                            'delivered' => 9,
                        ];
                        $currentStep = $statusOrder[$cargo->status] ?? 0;
                        @endphp

                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $currentStep >= 1 ? 'success' : 'secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($currentStep >= 1)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <span>1</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">Order Created</h6>
                                    <p class="text-muted small mb-0">Booking confirmed and pending pickup</p>
                                    <small class="text-muted">{{ $cargo->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $currentStep >= 2 ? 'success' : 'secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($currentStep >= 2)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <span>2</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">Picked Up</h6>
                                    <p class="text-muted small mb-0">Cargo collected from sender</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $currentStep >= 3 ? 'success' : 'secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($currentStep >= 3)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <span>3</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">In Transit</h6>
                                    <p class="text-muted small mb-0">Shipped to Bangladesh via Saudi Post</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $currentStep >= 4 ? 'success' : 'secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($currentStep >= 4)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <span>4</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">Customs Clearance</h6>
                                    <p class="text-muted small mb-0">Processing customs at Bangladesh</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $currentStep >= 5 ? 'success' : 'secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($currentStep >= 5)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <span>5</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">Bangladesh Hub</h6>
                                    <p class="text-muted small mb-0">Arrived at Bangladesh distribution center</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-{{ $currentStep >= 6 ? 'success' : 'secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($currentStep >= 6)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <span>6</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">Delivered</h6>
                                    <p class="text-muted small mb-0">Package delivered to receiver</p>
                                    @if($cargo->status == 'delivered')
                                    <small class="text-success">{{ $cargo->updated_at->format('d M Y, h:i A') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipment Details -->
            <div class="col-lg-4">
                <!-- Sender Info -->
                <div class="card card-custom p-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-user me-2 text-primary-custom"></i>Sender</h5>
                    <p class="mb-1"><strong>{{ $cargo->sender_name }}</strong></p>
                    <p class="text-muted mb-1">{{ $cargo->sender_city }}</p>
                    <p class="text-muted mb-0"><i class="fas fa-phone me-2"></i>{{ $cargo->sender_phone }}</p>
                </div>

                <!-- Receiver Info -->
                <div class="card card-custom p-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-user-check me-2 text-primary-custom"></i>Receiver</h5>
                    <p class="mb-1"><strong>{{ $cargo->receiver_name }}</strong></p>
                    <p class="text-muted mb-1">{{ $cargo->receiver_city }}</p>
                    <p class="text-muted mb-0"><i class="fas fa-phone me-2"></i>{{ $cargo->receiver_phone }}</p>
                </div>

                <!-- Package Info -->
                <div class="card card-custom p-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-box me-2 text-primary-custom"></i>Package Details</h5>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Cargo Type:</td>
                            <td><strong>{{ $cargo->cargoType?->name ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Weight:</td>
                            <td><strong>{{ $cargo->weight }} kg</strong></td>
                        </tr>
                        <tr>
                            <td>Quantity:</td>
                            <td><strong>{{ $cargo->quantity }}</strong></td>
                        </tr>
                        <tr>
                            <td>Declared Value:</td>
                            <td><strong>SAR {{ number_format($cargo->declared_value, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Total Amount -->
                <div class="card card-custom p-4 mb-4">
                    <h5 class="mb-3"><i class="fas fa-receipt me-2 text-primary-custom"></i>Payment</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Amount:</span>
                        <span class="fs-4 fw-bold text-success">SAR {{ number_format($cargo->total_amount, 2) }}</span>
                    </div>
                    <span class="badge bg-{{ $cargo->payment_status == 'paid' ? 'success' : 'warning' }} mt-2">
                        Payment {{ ucfirst($cargo->payment_status) }}
                    </span>
                </div>

                <!-- Actions -->
                <a href="{{ route('cargo', ['locale' => app()->getLocale()]) }}" class="btn btn-primary-custom w-100">
                    <i class="fas fa-plus me-2"></i>New Booking
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
