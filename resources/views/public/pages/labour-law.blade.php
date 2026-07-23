@extends('layouts.public')

@section('title', __('Labour Law Information') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="law-hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Labour Law Information')</h1>
        <p class="lead text-white">@lang('Important information for workers in Saudi Arabia')</p>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">@lang('Overview')</a>
                    <a href="#" class="list-group-item list-group-item-action">@lang('Work Contracts')</a>
                    <a href="#" class="list-group-item list-group-item-action">@lang('Working Hours')</a>
                    <a href="#" class="list-group-item list-group-item-action">@lang('Wages & Benefits')</a>
                    <a href="#" class="list-group-item list-group-item-action">@lang('Leave Policies')</a>
                    <a href="#" class="list-group-item list-group-item-action">@lang('Termination')</a>
                    <a href="#" class="list-group-item list-group-item-action">@lang('Dispute Resolution')</a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="law-content">
                    <h2>@lang('Saudi Arabian Labour Law Overview')</h2>
                    <p class="lead">@lang('Understanding your rights and responsibilities as a worker in Saudi Arabia.')</p>
                    
                    <h4>@lang('Key Rights')</h4>
                    <ul>
                        <li>@lang('Fair wages paid on time')</li>
                        <li>@lang('Safe working conditions')</li>
                        <li>@lang('Rest days and annual leave')</li>
                        <li>@lang('End-of-service benefits')</li>
                        <li>@lang('Freedom from discrimination')</li>
                        <li>@lang('Access to legal recourse')</li>
                    </ul>
                    
                    <h4>@lang('Employer Obligations')</h4>
                    <ul>
                        <li>@lang('Provide written contracts')</li>
                        <li>@lang('Pay wages without delay')</li>
                        <li>@lang('Provide suitable accommodation')</li>
                        <li>@lang('Arrange medical care')</li>
                        <li>@lang('Facilitate return travel on termination')</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>@lang('Note:')</strong> @lang('This information is for guidance only. For legal advice, please consult with a qualified lawyer.')
                    </div>
                    
                    <h4>@lang('Contact for Assistance')</h4>
                    <p>@lang('For questions about labour law or to report violations:')</p>
                    <ul>
                        <li>@lang('Ministry of Human Resources and Social Development')</li>
                        <li>@lang('Hotline: 19911')</li>
                        <li>@lang('Website: www.hrsd.gov.sa')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.law-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.law-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.law-content h2 {
    color: var(--primary);
    margin-bottom: 15px;
}
.law-content h4 {
    color: #333;
    margin-top: 30px;
    margin-bottom: 15px;
}
.law-content ul {
    margin-bottom: 20px;
}
.list-group-item.active {
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>
@endpush
