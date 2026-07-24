@extends('layouts.public')

@section('title', __('Our Services') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="services-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Our Services')</h1>
        <p class="lead text-white">@lang('Comprehensive travel, cargo, and investment solutions')</p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Umrah Packages -->
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-kaaba"></i>
                    </div>
                    <h3>@lang('Umrah Packages')</h3>
                    <p>@lang('Complete Umrah packages including visa, accommodation, transportation, and guided tours.')</p>
                    <a href="{{ route('services.umrah') }}" class="btn btn-success">@lang('View Packages') <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- Visa Services -->
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-passport"></i>
                    </div>
                    <h3>@lang('Visa Services')</h3>
                    <p>@lang('Professional visa processing for Saudi Arabia, UAE, and other destinations.')</p>
                    <a href="{{ route('services.visa') }}" class="btn btn-success">@lang('Learn More') <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- Air Tickets -->
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-airplane"></i>
                    </div>
                    <h3>@lang('Air Tickets')</h3>
                    <p>@lang('Competitive fares on all major airlines. Book flights to Saudi Arabia, Bangladesh, and worldwide.')</p>
                    <a href="{{ route('services.airticket') }}" class="btn btn-success">@lang('Book Now') <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- Hotel Booking -->
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3>@lang('Hotel Booking')</h3>
                    <p>@lang('Premium hotels near Haram in Makkah and Madinah. Budget to luxury options available.')</p>
                    <a href="{{ route('services.hotel') }}" class="btn btn-success">@lang('View Hotels') <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- Cargo Service -->
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3>@lang('Cargo Service')</h3>
                    <p>@lang('Reliable air and sea cargo services between Saudi Arabia and Bangladesh. Door-to-door delivery.')</p>
                    <a href="{{ route('cargo') }}" class="btn btn-success">@lang('Learn More') <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <!-- Investor Services -->
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h3>@lang('Investor Services')</h3>
                    <p>@lang('Business setup, investment licenses, MISA services, and company registration in Saudi Arabia.')</p>
                    <a href="{{ route('services.investor') }}" class="btn btn-success">@lang('Learn More') <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2>@lang('Need Help Planning Your Trip?')</h2>
        <p class="lead mb-4">@lang('Our team is ready to assist you with personalized travel solutions.')</p>
        <a href="{{ locale_route('contact') }}" class="btn btn-success btn-lg">
            <i class="bi bi-chat-dots"></i> @lang('Contact Us')
        </a>
    </div>
</section>

@endsection

@push('styles')
<style>
.services-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 80px 0;
}
.service-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
.service-icon {
    width: 80px;
    height: 80px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.service-icon i {
    font-size: 2rem;
    color: white;
}
.service-card h3 {
    color: var(--primary);
    margin-bottom: 15px;
}
.service-card p {
    color: #666;
    margin-bottom: 20px;
}
</style>
@endpush
