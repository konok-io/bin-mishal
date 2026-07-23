@extends('layouts.public')

@section('title', __('Refund & Cancellation Policy') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="legal-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Refund & Cancellation Policy')</h1>
        <p class="lead text-white">@lang('Our policy on refunds and cancellations')</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="legal-content">
            <h2>Refund Policy Overview</h2>
            <p>We understand that plans can change. Our refund policy is designed to be fair while covering our costs for services already rendered.</p>
            
            <h2>Flight Tickets</h2>
            <p>Refund eligibility for flight tickets depends on the fare type and airline policies:</p>
            <ul>
                <li><strong>Refundable Fares:</strong> Full refund minus processing fees if cancelled before departure</li>
                <li><strong>Non-Refundable Fares:</strong> Only taxes may be refundable; base fare is non-refundable</li>
                <li><strong>Cancellation by Airline:</strong> Full refund if flight is cancelled by the airline</li>
                <li><strong>Schedule Changes:</strong> Rebooking options available if airline changes schedule</li>
            </ul>
            
            <h2>Umrah Packages</h2>
            <p>Our cancellation policy for Umrah packages:</p>
            <ul>
                <li><strong>30+ days before departure:</strong> 75% refund (25% administrative fee)</li>
                <li><strong>15-30 days before departure:</strong> 50% refund</li>
                <li><strong>7-14 days before departure:</strong> 25% refund</li>
                <li><strong>Less than 7 days:</strong> No refund</li>
            </ul>
            <p>Visa fees, if already processed, are non-refundable by the authorities.</p>
            
            <h2>Visa Services</h2>
            <p>Visa processing fees are generally non-refundable once processing begins. If the visa is rejected, we will assist with reapplication but cannot guarantee approval.</p>
            
            <h2>Hotel Bookings</h2>
            <p>Hotel refund policies vary by property. Standard policy:</p>
            <ul>
                <li><strong>Free cancellation:</strong> Up to 7 days before check-in</li>
                <li><strong>Late cancellation:</strong> First night's charge</li>
                <li><strong>No-show:</strong> Full charge for first night</li>
            </ul>
            
            <h2>Cargo Services</h2>
            <p>For cargo shipments:</p>
            <ul>
                <li>Cancellations before pickup: Full refund minus admin fee</li>
                <li>Cancellations after pickup: No refund (shipment in transit)</li>
                <li>Failed delivery due to incorrect address: No refund</li>
            </ul>
            
            <h2>How to Request a Refund</h2>
            <p>To request a refund:</p>
            <ol>
                <li>Contact our customer service team</li>
                <li>Provide your booking reference number</li>
                <li>Explain the reason for cancellation</li>
                <li>Submit any required documentation</li>
            </ol>
            
            <h2>Processing Time</h2>
            <p>Refunds are processed within 14-30 business days. The actual time depends on your payment method and bank processing times.</p>
            
            <h2>Exceptions</h2>
            <p>Refunds may not be available for:</p>
            <ul>
                <li>Services already fully rendered</li>
                <li>Bookings made during promotional sales (unless specified)</li>
                <li>Services with explicit "no refund" terms</li>
                <li>Government fees and taxes (as per regulations)</li>
            </ul>
            
            <h2>Contact Us</h2>
            <p>For refund inquiries, please contact us:</p>
            <p><strong>Email:</strong> {{ settings('email', 'info@binmishal.com') }}</p>
            <p><strong>Phone:</strong> {{ settings('phone', '+966 XX XXX XXXX') }}</p>
            <p><strong>Hours:</strong> Sunday - Thursday, 9 AM - 6 PM</p>
            
            <p class="text-muted"><small>@lang('Last updated:') {{ now()->format('F d, Y') }}</small></p>
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
.legal-content p {
    line-height: 1.8;
    color: #555;
}
.legal-content ul, .legal-content ol {
    margin-bottom: 20px;
    padding-left: 25px;
}
.legal-content li {
    line-height: 1.8;
    margin-bottom: 8px;
}
</style>
@endpush
