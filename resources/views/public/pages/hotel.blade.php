@extends('layouts.public')

@section('title', __('Hotel Booking') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="hotel-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ locale_route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">@lang('Services')</a></li>
                <li class="breadcrumb-item active">@lang('Hotel Booking')</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Premium Hotel Booking')</h1>
        <p class="lead text-white">@lang('Hotels near Haram in Makkah and Madinah')</p>
    </div>
</section>

<!-- Search Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="search-form-card">
            <form method="GET" action="{{ route('contact') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">@lang('City')</label>
                    <select name="city" class="form-select" required>
                        <option value="">@lang('Select City')</option>
                        <option value="makkah">Makkah</option>
                        <option value="madinah">Madinah</option>
                        <option value="riyadh">Riyadh</option>
                        <option value="jeddah">Jeddah</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('Check In')</label>
                    <input type="date" name="check_in" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('Check Out')</label>
                    <input type="date" name="check_out" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('Guests')</label>
                    <select name="guests" class="form-select">
                        @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-search"></i> @lang('Search Hotels')
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Hotel Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">@lang('Hotel Categories')</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h3>5 @lang('Star Hotels')</h3>
                    <p>@lang('Luxury accommodations with premium amenities near Haram')</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-success">@lang('View Options')</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-star-half"></i>
                    </div>
                    <h3>4 @lang('Star Hotels')</h3>
                    <p>@lang('Comfortable hotels with excellent service and facilities')</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-success">@lang('View Options')</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3>3 @lang('Star Hotels')</h3>
                    <p>@lang('Quality budget-friendly options for comfortable stays')</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-success">@lang('View Options')</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Book With Us -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">@lang('Why Book Hotels With Us')</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <i class="bi bi-geo-alt text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('Prime Locations')</h4>
                <p>@lang('Hotels within walking distance to Haram')</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="bi bi-currency-dollar text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('Best Rates')</h4>
                <p>@lang('Competitive prices guaranteed')</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('Secure Booking')</h4>
                <p>@lang('Safe and reliable reservations')</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="bi bi-headset text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('24/7 Support')</h4>
                <p>@lang('Assistance whenever you need it')</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.hotel-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.breadcrumb-item a { color: rgba(255,255,255,0.8); }
.breadcrumb-item.active { color: white; }
.search-form-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.category-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s;
}
.category-card:hover {
    transform: translateY(-5px);
}
.category-icon {
    width: 80px;
    height: 80px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.category-icon i {
    font-size: 2rem;
    color: white;
}
.category-card h3 {
    color: var(--primary);
    margin-bottom: 10px;
}
.category-card p {
    color: #666;
    margin-bottom: 20px;
}
</style>
@endpush
