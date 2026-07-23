@extends('layouts.public')

@section('title', __('Visa Services') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="visa-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">@lang('Services')</a></li>
                <li class="breadcrumb-item active">@lang('Visa Services')</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Visa Services')</h1>
        <p class="lead text-white">@lang('Professional visa processing for Saudi Arabia and beyond')</p>
    </div>
</section>

<!-- Visa Types -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">@lang('Available Visa Types')</h2>
        <div class="row g-4">
            @php
            $visaTypes = \App\Models\VisaType::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            @endphp
            
            @forelse($visaTypes as $type)
            <div class="col-md-6 col-lg-4">
                <div class="visa-card">
                    <div class="visa-icon">
                        <i class="bi bi-passport"></i>
                    </div>
                    <h3>{{ $type->name }}</h3>
                    <div class="visa-details">
                        <span><i class="bi bi-clock"></i> {{ $type->processing_time ?? '5-7 days' }}</span>
                        <span><i class="bi bi-currency-dollar"></i> SAR {{ number_format($type->fee ?? 0) }}</span>
                    </div>
                    <p>{{ Str::limit($type->description, 100) }}</p>
                    <a href="{{ route('services.visa.service', $type->slug ?? $type->id) }}" class="btn btn-success">
                        @lang('Apply Now')
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                @php
                $defaultTypes = [
                    ['name' => 'Tourist Visa', 'fee' => 500, 'description' => 'For leisure travel and tourism purposes'],
                    ['name' => 'Business Visa', 'fee' => 750, 'description' => 'For business meetings and conferences'],
                    ['name' => 'Transit Visa', 'fee' => 300, 'description' => 'For travelers passing through Saudi Arabia'],
                    ['name' => 'Work Visa', 'fee' => 1000, 'description' => 'For employment purposes'],
                    ['name' => 'Family Visit Visa', 'fee' => 600, 'description' => 'For visiting family members'],
                    ['name' => 'Umrah Visa', 'fee' => 400, 'description' => 'For religious pilgrimage purposes'],
                ];
                @endphp
                @foreach($defaultTypes as $type)
                <div class="col-md-6 col-lg-4">
                    <div class="visa-card">
                        <div class="visa-icon">
                            <i class="bi bi-passport"></i>
                        </div>
                        <h3>{{ $type['name'] }}</h3>
                        <div class="visa-details">
                            <span><i class="bi bi-clock"></i> 5-7 days</span>
                            <span><i class="bi bi-currency-dollar"></i> SAR {{ number_format($type['fee']) }}</span>
                        </div>
                        <p>{{ $type['description'] }}</p>
                        <a href="{{ route('contact') }}" class="btn btn-success">
                            @lang('Apply Now')
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Requirements Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">@lang('General Requirements')</h2>
        <div class="row">
            <div class="col-lg-6">
                <h4><i class="bi bi-file-text text-success"></i> @lang('Documents Required')</h4>
                <ul class="requirements-list">
                    <li>@lang('Valid passport (minimum 6 months validity)')</li>
                    <li>@lang('Passport-size photographs (2 copies)')</li>
                    <li>@lang('Completed visa application form')</li>
                    <li>@lang('Proof of accommodation')</li>
                    <li>@lang('Round-trip flight itinerary')</li>
                    <li>@lang('Proof of financial means')</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <h4><i class="bi bi-info-circle text-success"></i> @lang('Important Information')</h4>
                <ul class="requirements-list">
                    <li>@lang('Processing time: 5-7 working days')</li>
                    <li>@lang('Visa fees are non-refundable')</li>
                    <li>@lang('Additional documents may be required')</li>
                    <li>@lang('Visa validity varies by type')</li>
                    <li>@lang('Insurance coverage recommended')</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2>@lang('Need Assistance?')</h2>
        <p class="lead mb-4">@lang('Our visa experts are here to help you every step of the way.')</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                <i class="bi bi-chat-dots"></i> @lang('Contact Us')
            </a>
            <a href="{{ route('appointment') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-calendar"></i> @lang('Book Appointment')
            </a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.visa-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.breadcrumb-item a { color: rgba(255,255,255,0.8); }
.breadcrumb-item.active { color: white; }
.visa-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform 0.3s;
}
.visa-card:hover {
    transform: translateY(-5px);
}
.visa-icon {
    width: 70px;
    height: 70px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.visa-icon i {
    font-size: 2rem;
    color: white;
}
.visa-card h3 {
    color: var(--primary);
    margin-bottom: 10px;
}
.visa-details {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 15px;
    color: #666;
}
.visa-details span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.requirements-list {
    list-style: none;
    padding: 0;
}
.requirements-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    padding-left: 30px;
    position: relative;
}
.requirements-list li::before {
    content: '\2713';
    position: absolute;
    left: 0;
    color: var(--primary);
    font-weight: bold;
}
</style>
@endpush
