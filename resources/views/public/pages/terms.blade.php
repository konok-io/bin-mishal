@extends('layouts.public')

@section('title', __('Terms of Service') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="legal-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Terms of Service')</h1>
        <p class="lead text-white">@lang('The terms and conditions governing your use of our services')</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="legal-content">
            @php
            $termsContent = \App\Models\Content\Page::where('slug', 'terms')->first();
            @endphp
            
            @if($termsContent && $termsContent->content)
                {!! $termsContent->content !!}
            @else
                <h2>Agreement to Terms</h2>
                <p>By accessing or using the services of {{ config('app.name') }}, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>
                
                <h2>Services Provided</h2>
                <p>{{ config('app.name') }} provides the following services:</p>
                <ul>
                    <li>Airline ticket booking</li>
                    <li>Umrah and Hajj package tours</li>
                    <li>Visa processing services</li>
                    <li>Hotel reservations</li>
                    <li>Cargo and freight services</li>
                    <li>Business and investment services</li>
                </ul>
                
                <h2>Booking Terms</h2>
                <h4>Reservation</h4>
                <p>By making a booking with us, you confirm that all information provided is accurate and complete. Bookings are subject to availability and confirmation.</p>
                
                <h4>Payment</h4>
                <p>Full payment is required at the time of booking unless otherwise specified. We accept payment via credit card, bank transfer, and other approved methods.</p>
                
                <h4>Pricing</h4>
                <p>All prices are subject to change without notice until booking is confirmed. Prices include applicable taxes unless stated otherwise.</p>
                
                <h2>Cancellation Policy</h2>
                <p>Cancellation fees apply based on the timing of cancellation:</p>
                <ul>
                    <li>More than 30 days before departure: 25% of total cost</li>
                    <li>15-30 days before departure: 50% of total cost</li>
                    <li>Less than 15 days before departure: 100% of total cost</li>
                </ul>
                <p>Specific services may have different cancellation policies.</p>
                
                <h2>Travel Documents</h2>
                <p>It is your responsibility to ensure that you have valid travel documents, including passports, visas, and vaccinations as required for your destination.</p>
                
                <h2>Limitation of Liability</h2>
                <p>{{ config('app.name') }} shall not be liable for any direct, indirect, or consequential damages arising from the use of our services or changes to travel plans.</p>
                
                <h2>Governing Law</h2>
                <p>These terms are governed by the laws of Saudi Arabia. Any disputes shall be resolved in the courts of Saudi Arabia.</p>
                
                <h2>Contact Information</h2>
                <p>For questions about these Terms of Service, please contact us:</p>
                <p><strong>Email:</strong> {{ settings('email', 'info@binmishal.com') }}</p>
                <p><strong>Phone:</strong> {{ settings('phone', '+966 XX XXX XXXX') }}</p>
                
                <p class="text-muted"><small>@lang('Last updated:') {{ now()->format('F d, Y') }}</small></p>
            @endif
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.legal-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.legal-content {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    max-width: 900px;
    margin: 0 auto;
}
.legal-content h2 {
    color: var(--primary);
    margin-top: 30px;
    margin-bottom: 15px;
}
.legal-content h4 {
    color: #333;
    margin-top: 20px;
    margin-bottom: 10px;
}
.legal-content p {
    line-height: 1.8;
    color: #555;
}
.legal-content ul {
    margin-bottom: 20px;
}
</style>
@endpush
