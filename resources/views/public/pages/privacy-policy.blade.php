@extends('layouts.public')

@section('title', __('Privacy Policy') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="legal-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Privacy Policy')</h1>
        <p class="lead text-white">@lang('How we collect, use, and protect your information')</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="legal-content">
            @php
            $privacyContent = \App\Models\Content\Page::where('slug', 'privacy-policy')->first();
            @endphp
            
            @if($privacyContent && $privacyContent->content)
                {!! $privacyContent->content !!}
            @else
                <h2>Introduction</h2>
                <p>{{ config('app.name') }} ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>
                
                <h2>Information We Collect</h2>
                <h4>Personal Information</h4>
                <p>We may collect personal information that you voluntarily provide to us, including:</p>
                <ul>
                    <li>Name, email address, phone number, and postal address</li>
                    <li>Passport information and travel documents</li>
                    <li>Payment information</li>
                    <li>Date of birth and nationality</li>
                </ul>
                
                <h4>Automatically Collected Information</h4>
                <p>When you visit our website, we automatically collect certain information, including:</p>
                <ul>
                    <li>IP address and browser type</li>
                    <li>Pages visited and time spent</li>
                    <li>Device information</li>
                    <li>Cookies and tracking data</li>
                </ul>
                
                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and improve our services</li>
                    <li>Process bookings and reservations</li>
                    <li>Send confirmations and updates</li>
                    <li>Respond to your inquiries</li>
                    <li>Send marketing communications (with consent)</li>
                    <li>Comply with legal obligations</li>
                </ul>
                
                <h2>Information Sharing</h2>
                <p>We do not sell your personal information. We may share your information with:</p>
                <ul>
                    <li>Service providers (airlines, hotels, visa authorities)</li>
                    <li>Payment processors</li>
                    <li>Legal authorities when required by law</li>
                </ul>
                
                <h2>Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the Internet is 100% secure.</p>
                
                <h2>Your Rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access your personal information</li>
                    <li>Correct inaccurate data</li>
                    <li>Request deletion of your data</li>
                    <li>Opt-out of marketing communications</li>
                </ul>
                
                <h2>Contact Us</h2>
                <p>If you have questions about this Privacy Policy, please contact us at:</p>
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
