@extends('layouts.public')

@section('title', __('Investor Services') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="investor-hero" style="background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); padding: 80px 0;">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Investor Services')</h1>
        <p class="lead text-white">@lang('Business setup, investment licenses, and company registration in Saudi Arabia')</p>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Services List -->
                @if(isset($services) && $services->count() > 0)
                    <div class="row g-4 mb-5">
                        @foreach($services as $service)
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        @if($service->icon)
                                            <div class="mb-3">
                                                <i class="{{ $service->icon }} fa-2x text-primary"></i>
                                            </div>
                                        @endif
                                        <h5 class="card-title">{{ $service->name }}</h5>
                                        <p class="card-text text-muted">{{ $service->description }}</p>
                                        @if($service->features)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($service->features as $feature)
                                                    <li class="mb-1">
                                                        <i class="bi bi-check-circle text-success me-2"></i>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Default Services -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-building fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">@lang('Business Setup')</h5>
                                    <p class="card-text text-muted">@lang('Complete company formation services including trade license, chamber of commerce registration, and operational permits.')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-file-earming-check fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">@lang('Investment License')</h5>
                                    <p class="card-text text-muted">@lang('MISA and investment license acquisition for foreign investors looking to establish presence in Saudi Arabia.')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-person-badge fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">@lang('Investor Visa')</h5>
                                    <p class="card-text text-muted">@lang('Investor and family visit visa processing with priority appointment scheduling.')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <i class="bi bi-diagram-3 fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">@lang('Branch Office')</h5>
                                    <p class="card-text text-muted">@lang('Establish branch offices or commercial registrations for multinational companies.')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Benefits Section -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">@lang('Why Invest in Saudi Arabia?')</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @lang('Vision 2030 economic diversification')
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @lang('100% foreign ownership allowed')
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @lang('Strategic geographic location')
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @lang('Tax incentives for investors')
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @lang('World-class infrastructure')
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        @lang('Growing consumer market')
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Inquiry Form -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>@lang('Investment Inquiry')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ locale_route('investor.inquiry') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="full_name" class="form-label">@lang('Full Name')</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">@lang('Email')</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">@lang('Phone')</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            @if(isset($services) && $services->count() > 0)
                            <div class="mb-3">
                                <label for="service_id" class="form-label">@lang('Service Interest')</label>
                                <select class="form-select" id="service_id" name="service_id" required>
                                    <option value="">@lang('Select a service')</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="company_name" class="form-label">@lang('Company Name')</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">@lang('Message')</label>
                                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                @lang('Submit Inquiry')
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">@lang('Contact Us')</h5>
                        <div class="mb-3">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>+966 XX XXX XXXX</span>
                        </div>
                        <div class="mb-3">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>investor@binmishal.com</span>
                        </div>
                        <div class="mb-3">
                            <i class="bi bi-whatsapp text-primary me-2"></i>
                            <span>+966 XX XXX XXXX</span>
                        </div>
                        <hr>
                        <p class="text-muted small mb-0">@lang('Our investment consultants are available Sunday to Thursday, 9 AM to 5 PM.')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.investor-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 80px 0;
}
</style>
@endpush
