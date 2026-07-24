@extends('layouts.public')

@section('title', __('Page Not Found'))

@section('content')
<section class="error-page">
    <div class="container">
        <div class="error-content text-center">
            <div class="error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h1 class="error-code">404</h1>
            <h2 class="error-title">@lang('Page Not Found')</h2>
            <p class="error-message">
                @lang("Sorry, the page you're looking for doesn't exist or has been moved.")
            </p>
            <div class="error-actions">
                <a href="{{ locale_route('home') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-house"></i> @lang('Go to Homepage')
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-success btn-lg">
                    <i class="bi bi-chat-dots"></i> @lang('Contact Us')
                </a>
            </div>
            
            <div class="mt-5">
                <h5 class="mb-3">@lang('Quick Links')</h5>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <a href="{{ route('services') }}" class="quick-link">
                                    <i class="bi bi-grid"></i>
                                    <span>@lang('Services')</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('services.umrah') }}" class="quick-link">
                                    <i class="bi bi-kaaba"></i>
                                    <span>@lang('Umrah')</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('cargo') }}" class="quick-link">
                                    <i class="bi bi-truck"></i>
                                    <span>@lang('Cargo')</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('careers') }}" class="quick-link">
                                    <i class="bi bi-briefcase"></i>
                                    <span>@lang('Careers')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.error-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.error-content {
    background: white;
    border-radius: 20px;
    padding: 60px;
    max-width: 700px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}
.error-icon {
    width: 120px;
    height: 120px;
    background: #fff3cd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
}
.error-icon i {
    font-size: 4rem;
    color: #ffc107;
}
.error-code {
    font-size: 8rem;
    font-weight: 800;
    color: var(--primary);
    line-height: 1;
    margin-bottom: 10px;
}
.error-title {
    font-size: 2rem;
    color: #333;
    margin-bottom: 15px;
}
.error-message {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}
.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}
.quick-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}
.quick-link:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-3px);
}
.quick-link i {
    font-size: 1.5rem;
    margin-bottom: 8px;
}
</style>
@endpush
