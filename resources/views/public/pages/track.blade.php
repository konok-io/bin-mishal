@extends('layouts.public')

@section('title', __('Track Your Order') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="track-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Track Your Order')</h1>
        <p class="lead text-white">@lang('Track your booking, visa, or cargo shipment')</p>
    </div>
</section>

<!-- Tracking Options -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Cargo Tracking -->
            <div class="col-md-6">
                <div class="track-card">
                    <div class="track-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>@lang('Track Cargo Shipment')</h3>
                    <form method="GET" action="{{ route('cargo.track', ':tracking') }}" id="cargo-track-form">
                        <div class="input-group mb-3">
                            <input type="text" name="tracking_number" class="form-control" 
                                   placeholder="@lang('Enter Tracking Number')" required>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <p class="text-muted small">@lang('Your tracking number starts with BM-')</p>
                </div>
            </div>
            
            <!-- Booking Tracking -->
            <div class="col-md-6">
                <div class="track-card">
                    <div class="track-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <h3>@lang('Track Booking')</h3>
                    <form method="GET" action="{{ locale_route('contact') }}">
                        <div class="input-group mb-3">
                            <input type="text" name="booking_ref" class="form-control" 
                                   placeholder="@lang('Enter Booking Reference')" required>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    <p class="text-muted small">@lang('Your booking reference was sent via email')</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tracking Timeline -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">@lang('Common Tracking Status')</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-icon success">
                    <i class="bi bi-check"></i>
                </div>
                <div class="timeline-content">
                    <h4>@lang('Booked')</h4>
                    <p>@lang('Your order has been received and is being processed')</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon info">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="timeline-content">
                    <h4>@lang('Processing')</h4>
                    <p>@lang('Your order is being prepared for shipment')</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon warning">
                    <i class="bi bi-airplane"></i>
                </div>
                <div class="timeline-content">
                    <h4>@lang('In Transit')</h4>
                    <p>@lang('Your shipment is on its way')</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon secondary">
                    <i class="bi bi-building"></i>
                </div>
                <div class="timeline-content">
                    <h4>@lang('At Customs')</h4>
                    <p>@lang('Your shipment is going through customs clearance')</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon success">
                    <i class="bi bi-house"></i>
                </div>
                <div class="timeline-content">
                    <h4>@lang('Delivered')</h4>
                    <p>@lang('Your shipment has been delivered')</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.track-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.track-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    height: 100%;
}
.track-icon {
    width: 80px;
    height: 80px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.track-icon i {
    font-size: 2rem;
    color: white;
}
.track-card h3 {
    color: var(--primary);
    margin-bottom: 20px;
}
.timeline {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}
.timeline-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}
.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.timeline-icon.success { background: var(--success); color: white; }
.timeline-icon.info { background: var(--info); color: white; }
.timeline-icon.warning { background: var(--warning); color: white; }
.timeline-icon.secondary { background: #6c757d; color: white; }
.timeline-content h4 { color: #333; margin-bottom: 5px; }
.timeline-content p { color: #666; margin: 0; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('cargo-track-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var tracking = this.querySelector('input[name="tracking_number"]').value;
    if (tracking) {
        window.location.href = '{{ route("cargo.track", ":tracking") }}'.replace(':tracking', tracking);
    }
});
</script>
@endpush
