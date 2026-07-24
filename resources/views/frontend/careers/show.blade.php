@extends('layouts.public')

@section('title', $job->title . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="job-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ locale_route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('careers') }}">@lang('Careers')</a></li>
                <li class="breadcrumb-item active">{{ $job->title }}</li>
            </ol>
        </nav>
        <div class="job-header">
            <h1 class="display-5 fw-bold">{{ $job->title }}</h1>
            <div class="job-badges">
                <span class="badge bg-success">{{ Job::DEPARTMENTS[$job->department] ?? $job->department }}</span>
                <span class="badge bg-outline-light">{{ Job::EMPLOYMENT_TYPES[$job->employment_type] ?? $job->employment_type }}</span>
                <span class="badge bg-outline-light">{{ Job::EXPERIENCE_LEVELS[$job->experience_level] ?? $job->experience_level }}</span>
            </div>
            <div class="job-info">
                <span><i class="bi bi-geo-alt"></i> {{ $job->location }}</span>
                <span><i class="bi bi-calendar"></i> @lang('Deadline'): {{ $job->deadline ? $job->deadline->format('M d, Y') : __('Open until filled') }}</span>
                @if($job->salary_visible && $job->salary_min)
                <span><i class="bi bi-currency-dollar"></i> SAR {{ number_format($job->salary_min) }}@if($job->salary_max) - SAR {{ number_format($job->salary_max) }}@endif</span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Job Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5><i class="bi bi-exclamation-triangle"></i> @lang('Please fix the following errors:')</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Job Description -->
                <div class="job-section mb-4">
                    <h3><i class="bi bi-file-text text-success"></i> @lang('Job Description')</h3>
                    {!! nl2br($job->description) !!}
                </div>

                <!-- Responsibilities -->
                @if($job->responsibilities)
                <div class="job-section mb-4">
                    <h3><i class="bi bi-list-check text-success"></i> @lang('Responsibilities')</h3>
                    {!! nl2br($job->responsibilities) !!}
                </div>
                @endif

                <!-- Requirements -->
                @if($job->requirements)
                <div class="job-section mb-4">
                    <h3><i class="bi bi-clipboard-check text-success"></i> @lang('Requirements')</h3>
                    {!! nl2br($job->requirements) !!}
                </div>
                @endif

                <!-- Benefits -->
                @if($job->benefits)
                <div class="job-section mb-4">
                    <h3><i class="bi bi-gift text-success"></i> @lang('Benefits')</h3>
                    {!! nl2br($job->benefits) !!}
                </div>
                @endif

                <!-- Apply Section -->
                <div class="apply-section mt-5" id="apply">
                    <h3><i class="bi bi-send text-success"></i> @lang('Apply for this Position')</h3>
                    
                    <form method="POST" action="{{ route('careers.apply', $job->slug ?? $job->id) }}" 
                          enctype="multipart/form-data" class="apply-form mt-4">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">@lang('Full Name') <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" required
                                    value="{{ old('full_name') }}"
                                    placeholder="@lang('Enter your full name')">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">@lang('Email Address') <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required
                                    value="{{ old('email') }}"
                                    placeholder="@lang('your@email.com')">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">@lang('Country Code') <span class="text-danger">*</span></label>
                                <select name="phone_country_code" class="form-select" required>
                                    <option value="+966" {{ old('phone_country_code') == '+966' ? 'selected' : '' }}>+966 (KSA)</option>
                                    <option value="+880" {{ old('phone_country_code') == '+880' ? 'selected' : '' }}>+880 (BD)</option>
                                    <option value="+971" {{ old('phone_country_code') == '+971' ? 'selected' : '' }}>+971 (UAE)</option>
                                    <option value="+974" {{ old('phone_country_code') == '+974' ? 'selected' : '' }}>+974 (Qatar)</option>
                                    <option value="+1" {{ old('phone_country_code') == '+1' ? 'selected' : '' }}>+1 (USA)</option>
                                    <option value="+44" {{ old('phone_country_code') == '+44' ? 'selected' : '' }}>+44 (UK)</option>
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">@lang('Phone Number') <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" required
                                    value="{{ old('phone') }}"
                                    placeholder="@lang('Enter your phone number')">
                            </div>

                            <div class="col-12">
                                <label class="form-label">@lang('Upload CV/Resume') <span class="text-danger">*</span></label>
                                <input type="file" name="cv" class="form-control" required accept=".pdf,.doc,.docx">
                                <small class="text-muted">@lang('Maximum file size: 5MB. Accepted formats: PDF, DOC, DOCX')</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">@lang('Cover Letter')</label>
                                <textarea name="cover_letter" class="form-control" rows="5"
                                    placeholder="@lang('Tell us why you are a good fit for this position...')">{{ old('cover_letter') }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="consent" class="form-check-input" id="consent" required>
                                    <label class="form-check-label" for="consent">
                                        @lang('I agree to the') <a href="{{ route('privacy-policy') }}" target="_blank">@lang('Privacy Policy')</a> 
                                        @lang('and consent to the processing of my personal data for recruitment purposes.')
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-send"></i> @lang('Submit Application')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card job-summary-card sticky-top" style="top: 100px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">@lang('Job Summary')</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>@lang('Department')</strong></td>
                                <td>{{ Job::DEPARTMENTS[$job->department] ?? $job->department }}</td>
                            </tr>
                            <tr>
                                <td><strong>@lang('Location')</strong></td>
                                <td>{{ $job->location }}</td>
                            </tr>
                            <tr>
                                <td><strong>@lang('Employment Type')</strong></td>
                                <td>{{ Job::EMPLOYMENT_TYPES[$job->employment_type] ?? $job->employment_type }}</td>
                            </tr>
                            <tr>
                                <td><strong>@lang('Experience')</strong></td>
                                <td>{{ Job::EXPERIENCE_LEVELS[$job->experience_level] ?? $job->experience_level }}</td>
                            </tr>
                            @if($job->deadline)
                            <tr>
                                <td><strong>@lang('Deadline')</strong></td>
                                <td>{{ $job->deadline->format('M d, Y') }}</td>
                            </tr>
                            @endif
                            @if($job->salary_visible && $job->salary_min)
                            <tr>
                                <td><strong>@lang('Salary Range')</strong></td>
                                <td>SAR {{ number_format($job->salary_min) }}@if($job->salary_max) - SAR {{ number_format($job->salary_max) }}@endif</td>
                            </tr>
                            @endif
                        </table>
                        
                        <a href="#apply" class="btn btn-success w-100 mt-3">
                            <i class="bi bi-send"></i> @lang('Apply Now')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.job-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 40px 0 60px;
    color: white;
}
.breadcrumb-item a {
    color: rgba(255,255,255,0.8);
}
.breadcrumb-item.active {
    color: white;
}
.job-badges {
    display: flex;
    gap: 10px;
    margin: 15px 0;
}
.badge.bg-outline-light {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.5);
    color: white;
}
.job-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    font-size: 16px;
}
.job-info span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.job-section {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.job-section h3 {
    color: var(--primary);
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--accent);
}
.apply-section {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
}
.apply-form {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.job-summary-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endpush
