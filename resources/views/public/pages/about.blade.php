@extends('public.layouts.master')

@section('title', __('app.about') . ' - ' . __('app.app_name'))

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="mb-3">{{ __('app.about') }}</h1>
        <p class="lead opacity-75">Learn more about Bin Mishal Travel - Your trusted partner for Umrah & Travel Services</p>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="mb-4">Welcome to {{ __('app.app_name') }}</h2>
                <p class="lead text-muted mb-4">
                    We are a leading travel agency based in Saudi Arabia, dedicated to serving Bangladeshi expatriates with premium travel services since 2010.
                </p>
                <p class="text-muted mb-4">
                    Our mission is to provide hassle-free travel experiences, especially for Umrah pilgrims. We offer comprehensive packages that include visa processing, accommodation, transportation, and guided tours.
                </p>
                <h5 class="mb-3">Why Choose Us?</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 10+ Years of Experience</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Licensed & Government Approved</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 24/7 Customer Support</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Best Price Guarantee</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Experienced Guides</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); height: 400px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-mosque fa-10x text-white opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="display-4 fw-bold text-primary-custom">{{ __('home.experience_years') }}+</div>
                <p class="text-muted">Years Experience</p>
            </div>
            <div class="col-md-3">
                <div class="display-4 fw-bold text-primary-custom">{{ __('home.customers_served') }}+</div>
                <p class="text-muted">Happy Customers</p>
            </div>
            <div class="col-md-3">
                <div class="display-4 fw-bold text-primary-custom">{{ __('home.tickets_issued') }}+</div>
                <p class="text-muted">Tickets Issued</p>
            </div>
            <div class="col-md-3">
                <div class="display-4 fw-bold text-primary-custom">4.9</div>
                <p class="text-muted">Customer Rating</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Leadership Team</h2>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-custom text-center p-4">
                    <img src="https://ui-avatars.com/api/?name=CEO&background=006C35&color=fff&size=150" class="rounded-circle mx-auto mb-3" alt="CEO">
                    <h5>Ahmed Al-Mishal</h5>
                    <p class="text-muted mb-2">Founder & CEO</p>
                    <p class="small text-muted">Leading the company with 15+ years in travel industry</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-custom text-center p-4">
                    <img src="https://ui-avatars.com/api/?name=Manager&background=006C35&color=fff&size=150" class="rounded-circle mx-auto mb-3" alt="Manager">
                    <h5>Mohammad Khan</h5>
                    <p class="text-muted mb-2">Operations Manager</p>
                    <p class="small text-muted">Ensuring smooth operations for all our services</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-custom text-center p-4">
                    <img src="https://ui-avatars.com/api/?name=Director&background=006C35&color=fff&size=150" class="rounded-circle mx-auto mb-3" alt="Director">
                    <h5>Rahman Hassan</h5>
                    <p class="text-muted mb-2">Visa Director</p>
                    <p class="small text-muted">Expert in Saudi visa processing procedures</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
