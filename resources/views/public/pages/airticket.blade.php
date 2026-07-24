@extends('layouts.public')

@section('title', __('Air Tickets') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="airticket-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ locale_route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">@lang('Services')</a></li>
                <li class="breadcrumb-item active">@lang('Air Tickets')</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold text-white mb-3">@lang('Book Your Flight')</h1>
        <p class="lead text-white">@lang('Competitive fares to Saudi Arabia, Bangladesh, and worldwide')</p>
    </div>
</section>

<!-- Flight Search Form -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="search-form-card">
            <form method="GET" action="{{ locale_route('contact') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">@lang('From')</label>
                    <select name="from" class="form-select" required>
                        <option value="">@lang('Select City')</option>
                        <option value="RUH">Riyadh (RUH)</option>
                        <option value="JED">Jeddah (JED)</option>
                        <option value="MED">Medina (MED)</option>
                        <option value="DAM">Dammam (DAM)</option>
                        <option value="DAC">Dhaka (DAC)</option>
                        <option value="CGP">Chittagong (CGP)</option>
                        <option value="DXB">Dubai (DXB)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('To')</label>
                    <select name="to" class="form-select" required>
                        <option value="">@lang('Select City')</option>
                        <option value="RUH">Riyadh (RUH)</option>
                        <option value="JED">Jeddah (JED)</option>
                        <option value="MED">Medina (MED)</option>
                        <option value="DAM">Dammam (DAM)</option>
                        <option value="DAC">Dhaka (DAC)</option>
                        <option value="CGP">Chittagong (CGP)</option>
                        <option value="DXB">Dubai (DXB)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('Departure')</label>
                    <input type="date" name="departure" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('Return')</label>
                    <input type="date" name="return" class="form-control" min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('Passengers')</label>
                    <select name="passengers" class="form-select">
                        @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? __('Passenger') : __('Passengers') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-search"></i> @lang('Search Flights')
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Popular Routes -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">@lang('Popular Routes')</h2>
        <div class="row g-4">
            @php
            $routes = \App\Models\FlightRoute::where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->limit(6)
                ->get();
            @endphp
            
            @forelse($routes as $route)
            <div class="col-md-6 col-lg-4">
                <div class="route-card">
                    <div class="route-path">
                        <div class="route-city">
                            <strong>{{ $route->from_city }}</strong>
                            <span>{{ $route->from_country }}</span>
                        </div>
                        <div class="route-arrow">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <div class="route-city">
                            <strong>{{ $route->to_city }}</strong>
                            <span>{{ $route->to_country }}</span>
                        </div>
                    </div>
                    <div class="route-price">
                        <span class="from">@lang('From')</span>
                        <span class="amount">SAR {{ number_format($route->price ?? 0) }}</span>
                    </div>
                    @if($route->airline)
                    <div class="route-airline">
                        <small>{{ $route->airline }}</small>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-md-6 col-lg-4">
                <div class="route-card">
                    <div class="route-path">
                        <div class="route-city">
                            <strong>Riyadh</strong>
                            <span>Saudi Arabia</span>
                        </div>
                        <div class="route-arrow">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <div class="route-city">
                            <strong>Dhaka</strong>
                            <span>Bangladesh</span>
                        </div>
                    </div>
                    <div class="route-price">
                        <span class="from">@lang('From')</span>
                        <span class="amount">SAR 850</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="route-card">
                    <div class="route-path">
                        <div class="route-city">
                            <strong>Jeddah</strong>
                            <span>Saudi Arabia</span>
                        </div>
                        <div class="route-arrow">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <div class="route-city">
                            <strong>Chittagong</strong>
                            <span>Bangladesh</span>
                        </div>
                    </div>
                    <div class="route-price">
                        <span class="from">@lang('From')</span>
                        <span class="amount">SAR 920</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="route-card">
                    <div class="route-path">
                        <div class="route-city">
                            <strong>Dammam</strong>
                            <span>Saudi Arabia</span>
                        </div>
                        <div class="route-arrow">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <div class="route-city">
                            <strong>Sylhet</strong>
                            <span>Bangladesh</span>
                        </div>
                    </div>
                    <div class="route-price">
                        <span class="from">@lang('From')</span>
                        <span class="amount">SAR 780</span>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Airlines -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">@lang('We Work With Major Airlines')</h2>
        <div class="d-flex justify-content-center gap-5 flex-wrap">
            <div class="airline-logo">Saudi Arabian Airlines</div>
            <div class="airline-logo">Biman Bangladesh</div>
            <div class="airline-logo">Flydubai</div>
            <div class="airline-logo">Emirates</div>
            <div class="airline-logo">Qatar Airways</div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.airticket-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 60px 0;
}
.breadcrumb-item a { color: rgba(255,255,255,0.8); }
.breadcrumb-item.active { color: white; }
.search-form-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
.route-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    text-align: center;
}
.route-path {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-bottom: 15px;
}
.route-city {
    text-align: center;
}
.route-city strong {
    display: block;
    font-size: 1.1rem;
}
.route-city span {
    color: #666;
    font-size: 0.9rem;
}
.route-arrow {
    color: var(--primary);
}
.route-arrow i {
    font-size: 1.5rem;
}
.route-price {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}
.route-price .from {
    display: block;
    color: #666;
    font-size: 12px;
}
.route-price .amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
}
.airline-logo {
    padding: 20px 30px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    font-weight: 600;
    color: #333;
}
</style>
@endpush
