@extends('layouts.public')

@section('title', $slug . ' - ' . config('app.name'))

@section('content')
@php
$package = \App\Models\UmrahPackage::where('slug', $slug)->orWhere('id', $slug)->first();
@endphp

@if(!$package)
<section class="py-5">
    <div class="container text-center">
        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
        <h2 class="mt-3">@lang('Package Not Found')</h2>
        <p>@lang('The requested Umrah package could not be found.')</p>
        <a href="{{ route('services.umrah') }}" class="btn btn-success">@lang('Back to Packages')</a>
    </div>
</section>
@else
<!-- Hero Section -->
<section class="package-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services.umrah') }}">@lang('Umrah Packages')</a></li>
                <li class="breadcrumb-item active">{{ $package->name }}</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white">{{ $package->name }}</h1>
        <div class="package-badges mt-3">
            <span class="badge bg-light text-dark">{{ $package->duration }} @lang('Days')</span>
            <span class="badge bg-light text-dark">{{ $package->hotel_category ?? 'Standard' }} @lang('Hotel')</span>
        </div>
    </div>
</section>

<!-- Package Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Description -->
                <div class="package-section">
                    <h3><i class="bi bi-info-circle text-success"></i> @lang('Package Description')</h3>
                    {!! nl2br($package->description ?? '') !!}
                </div>

                <!-- Includes -->
                @if($package->inclusions)
                <div class="package-section">
                    <h3><i class="bi bi-check-circle text-success"></i> @lang('Package Includes')</h3>
                    {!! nl2br($package->inclusions) !!}
                </div>
                @endif

                <!-- Itinerary -->
                @if($package->itinerary)
                <div class="package-section">
                    <h3><i class="bi bi-calendar-week text-success"></i> @lang('Sample Itinerary')</h3>
                    {!! nl2br($package->itinerary) !!}
                </div>
                @endif

                <!-- Hotels -->
                @if($package->hotels)
                <div class="package-section">
                    <h3><i class="bi bi-building text-success"></i> @lang('Hotel Information')</h3>
                    {!! nl2br($package->hotels) !!}
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="booking-card sticky-top" style="top: 100px;">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">@lang('Book This Package')</h4>
                    </div>
                    <div class="card-body">
                        <div class="price-display text-center mb-4">
                            <span class="label">@lang('Price per person')</span>
                            <span class="amount">SAR {{ number_format($package->price) }}</span>
                        </div>

                        <form method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">@lang('Travel Date')</label>
                                <input type="date" name="travel_date" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">@lang('Number of Travelers')</label>
                                <select name="travelers" class="form-select" required>
                                    @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? __('Person') : __('Persons') }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang('Full Name')</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang('Email')</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang('Phone')</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-calendar-check"></i> @lang('Request Booking')
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('contact') }}" class="text-muted">
                                <i class="bi bi-chat-dots"></i> @lang('Need help? Contact us')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
.package-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.breadcrumb-item a { color: rgba(255,255,255,0.8); }
.breadcrumb-item.active { color: white; }
.package-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.package-section h3 {
    color: var(--primary);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--accent);
}
.booking-card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}
.price-display {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}
.price-display .label {
    display: block;
    color: #666;
    font-size: 14px;
}
.price-display .amount {
    display: block;
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary);
}
</style>
@endpush
