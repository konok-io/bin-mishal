@extends('layouts.public')

@section('title', __('Umrah Packages') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="umrah-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ locale_route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">@lang('Services')</a></li>
                <li class="breadcrumb-item active">@lang('Umrah Packages')</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Umrah Packages')</h1>
        <p class="lead text-white">@lang('Spiritually fulfilling Umrah experiences with complete package solutions')</p>
    </div>
</section>

<!-- Package Filters -->
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">@lang('Duration')</label>
                <select name="duration" class="form-select">
                    <option value="">@lang('Any Duration')</option>
                    <option value="7">7 @lang('Days')</option>
                    <option value="10">10 @lang('Days')</option>
                    <option value="14">14 @lang('Days')</option>
                    <option value="21">21 @lang('Days')</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">@lang('Budget')</label>
                <select name="budget" class="form-select">
                    <option value="">@lang('Any Budget')</option>
                    <option value="economy">@lang('Economy')</option>
                    <option value="standard">@lang('Standard')</option>
                    <option value="premium">@lang('Premium')</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">@lang('Month')</label>
                <select name="month" class="form-select">
                    <option value="">@lang('Any Month')</option>
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-search"></i> @lang('Search Packages')
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Packages Grid -->
<section class="py-5">
    <div class="container">
        @php
        $packages = \App\Models\UmrahPackage::where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('price')
            ->limit(12)
            ->get();
        @endphp
        
        @forelse($packages as $package)
        <div class="package-card mb-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    @if($package->image)
                    <img src="{{ $package->image }}" alt="{{ $package->name }}" class="img-fluid rounded">
                    @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                    </div>
                    @endif
                </div>
                <div class="col-md-5">
                    <span class="badge bg-warning text-dark mb-2">
                        @if($package->is_featured) @lang('Featured') @endif
                    </span>
                    <h3>{{ $package->name }}</h3>
                    <div class="package-meta">
                        <span><i class="bi bi-calendar"></i> {{ $package->duration }} @lang('Days')</span>
                        <span><i class="bi bi-building"></i> {{ $package->hotel_category ?? 'Standard' }} @lang('Hotel')</span>
                        <span><i class="bi bi-people"></i> {{ $package->group_size ?? 'Group' }}</span>
                    </div>
                    <p class="mt-2">{{ Str::limit($package->description, 150) }}</p>
                </div>
                <div class="col-md-3 text-md-end">
                    <div class="package-price">
                        <span class="price-label">@lang('Starting from')</span>
                        <span class="price">SAR {{ number_format($package->price) }}</span>
                        <span class="per-person">@lang('per person')</span>
                    </div>
                    <a href="{{ route('services.umrah.package', $package->slug ?? $package->id) }}" class="btn btn-success mt-3">
                        @lang('View Details')
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
            <h4 class="mt-3">@lang('No packages available')</h4>
            <p>@lang('Please check back soon for our Umrah packages.')</p>
        </div>
        @endforelse
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">@lang('Why Book With Us')</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('Licensed Agency')</h4>
                <p>@lang('Officially licensed by Saudi tourism authorities')</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="bi bi-star text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('5-Star Hotels')</h4>
                <p>@lang('Premium accommodation near Haram')</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="bi bi-headset text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('24/7 Support')</h4>
                <p>@lang('Round-the-clock assistance during your trip')</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="bi bi-currency-dollar text-success" style="font-size: 3rem;"></i>
                <h4 class="mt-3">@lang('Best Prices')</h4>
                <p>@lang('Competitive pricing with no hidden costs')</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.umrah-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.breadcrumb-item a { color: rgba(255,255,255,0.8); }
.breadcrumb-item.active { color: white; }
.package-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}
.package-meta {
    display: flex;
    gap: 20px;
    color: #666;
}
.package-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.package-price {
    text-align: right;
}
.price-label {
    display: block;
    color: #666;
    font-size: 14px;
}
.price {
    display: block;
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary);
}
.per-person {
    color: #666;
    font-size: 14px;
}
</style>
@endpush
