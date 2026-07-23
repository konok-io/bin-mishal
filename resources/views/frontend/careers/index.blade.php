@extends('layouts.public')

@section('title', __('Careers') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="careers-hero">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bold mb-3">@lang('Join Our Team')</h1>
            <p class="lead">@lang('Explore exciting career opportunities at Bin Mishal Travels')</p>
        </div>
    </div>
</section>

<!-- Job Listings -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="card filters-card sticky-top" style="top: 100px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> @lang('Filter Jobs')</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('careers') }}">
                            <div class="mb-3">
                                <label class="form-label">@lang('Search')</label>
                                <input type="text" name="search" class="form-control" 
                                    value="{{ request('search') }}" 
                                    placeholder="@lang('Search jobs...')">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang('Department')</label>
                                <select name="department" class="form-select">
                                    <option value="">@lang('All Departments')</option>
                                    @foreach($departments as $key => $dept)
                                    <option value="{{ $key }}" {{ request('department') == $key ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang('Employment Type')</label>
                                <select name="type" class="form-select">
                                    <option value="">@lang('All Types')</option>
                                    @foreach($employmentTypes as $key => $type)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang('Location')</label>
                                <input type="text" name="location" class="form-control" 
                                    value="{{ request('location') }}" 
                                    placeholder="@lang('City or region...')">
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-search"></i> @lang('Apply Filters')
                            </button>
                            
                            @if(request()->anyFilled(['search', 'department', 'type', 'location']))
                            <a href="{{ route('careers') }}" class="btn btn-outline-secondary w-100 mt-2">
                                @lang('Clear Filters')
                            </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Jobs List -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>{{ $jobs->total() }} @lang('open positions')</h4>
                </div>

                @forelse($jobs as $job)
                <div class="job-card mb-4 {{ $job->is_featured ? 'featured' : '' }}">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            @if($job->is_featured)
                            <span class="badge bg-warning text-dark mb-2">
                                <i class="bi bi-star-fill"></i> @lang('Featured')
                            </span>
                            @endif
                            <h4 class="job-title">{{ $job->title }}</h4>
                            <div class="job-meta">
                                <span><i class="bi bi-building"></i> {{ $job->department }}</span>
                                <span><i class="bi bi-geo-alt"></i> {{ $job->location }}</span>
                                <span><i class="bi bi-briefcase"></i> {{ Job::EMPLOYMENT_TYPES[$job->employment_type] ?? $job->employment_type }}</span>
                            </div>
                            @if($job->salary_visible && $job->salary_min)
                            <div class="job-salary">
                                <i class="bi bi-currency-dollar"></i>
                                @lang('Salary'): SAR {{ number_format($job->salary_min) }}
                                @if($job->salary_max)
                                - SAR {{ number_format($job->salary_max) }}
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            @if($job->deadline)
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar"></i> @lang('Deadline'): {{ $job->deadline->format('M d, Y') }}
                            </p>
                            @endif
                            <a href="{{ route('careers.show', $job->slug ?? $job->id) }}" class="btn btn-success">
                                @lang('View Details') <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info text-center">
                    <i class="bi bi-briefcase" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">@lang('No jobs found')</h4>
                    <p>@lang('Try adjusting your filters or check back later for new opportunities.')</p>
                    @if(request()->anyFilled(['search', 'department', 'type', 'location']))
                    <a href="{{ route('careers') }}" class="btn btn-success">
                        @lang('Clear Filters')
                    </a>
                    @endif
                </div>
                @endforelse

                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</section>

<!-- Why Join Us -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">@lang('Why Join Bin Mishal Travels?')</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-globe text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">@lang('Global Opportunities')</h4>
                    <p class="text-muted">@lang('Work with international clients and expand your horizons across Saudi Arabia, Bangladesh, and beyond.')</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-mortarboard text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">@lang('Growth & Development')</h4>
                    <p class="text-muted">@lang('Continuous learning opportunities, training programs, and clear career advancement paths.')</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-heart text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">@lang('Great Benefits')</h4>
                    <p class="text-muted">@lang('Competitive salaries, health insurance, annual leave, and performance bonuses.')</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.careers-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 80px 0;
}
.job-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.job-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
.job-card.featured {
    border-left: 4px solid #ffc107;
}
.job-title {
    color: #333;
    margin-bottom: 10px;
}
.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    color: #666;
    font-size: 14px;
}
.job-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.job-salary {
    color: var(--primary);
    font-weight: 600;
    margin-top: 10px;
}
.filters-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
</style>
@endpush
