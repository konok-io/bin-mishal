@extends('layouts.public')

@section('title', __('Visa Status Checker') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="visa-checker-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Visa Status Checker')</h1>
        <p class="lead text-white">@lang('Check the status of your visa application')</p>
    </div>
</section>

<!-- Checker Form -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="checker-card">
                    <form method="GET" action="{{ locale_route('contact') }}" class="text-center">
                        <div class="mb-4">
                            <i class="bi bi-search text-success" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">@lang('Check Your Visa Status')</h3>
                            <p class="text-muted">@lang('Enter your visa application reference number')</p>
                        </div>
                        
                        <div class="mb-4">
                            <input type="text" name="visa_ref" class="form-control form-control-lg text-center" 
                                   placeholder="@lang('Enter Reference Number')" required>
                        </div>
                        
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control text-center" 
                                   placeholder="@lang('Enter Your Email')">
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-search"></i> @lang('Check Status')
                        </button>
                    </form>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        @lang('Need assistance?') <a href="{{ locale_route('contact') }}">@lang('Contact us')</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">@lang('How to Check Your Status')</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="step-number">1</div>
                <h4>@lang('Find Your Reference')</h4>
                <p>@lang('Your reference number is in your confirmation email')</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="step-number">2</div>
                <h4>@lang('Enter Details')</h4>
                <p>@lang('Enter your reference number and email')</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="step-number">3</div>
                <h4>@lang('View Status')</h4>
                <p>@lang('Get instant updates on your application')</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.visa-checker-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.checker-card {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.step-number {
    width: 60px;
    height: 60px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0 auto 15px;
}
</style>
@endpush
