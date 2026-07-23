@extends('layouts.public')

@section('title', $slug . ' - ' . config('app.name'))

@section('content')
@php
$visaType = \App\Models\VisaType::where('slug', $slug)->orWhere('id', $slug)->first();
@endphp

<!-- Hero Section -->
<section class="visa-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services.visa') }}">@lang('Visa Services')</a></li>
                <li class="breadcrumb-item active">{{ $visaType->name ?? $slug }}</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white">{{ $visaType->name ?? $slug }}</h1>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @if($visaType)
                <div class="visa-section">
                    <h3>@lang('Description')</h3>
                    {!! nl2br($visaType->description ?? '') !!}
                </div>

                @if($visaType->requirements)
                <div class="visa-section">
                    <h3>@lang('Requirements')</h3>
                    {!! nl2br($visaType->requirements) !!}
                </div>
                @endif

                @if($visaType->documents_required)
                <div class="visa-section">
                    <h3>@lang('Documents Required')</h3>
                    {!! nl2br($visaType->documents_required) !!}
                </div>
                @endif
                @else
                <div class="alert alert-info">
                    <p>@lang('Detailed information coming soon.')</p>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="apply-card sticky-top" style="top: 100px;">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">@lang('Apply for Visa')</h4>
                    </div>
                    <div class="card-body">
                        @if($visaType)
                        <div class="price-row mb-3">
                            <span>@lang('Visa Fee'):</span>
                            <strong>SAR {{ number_format($visaType->fee ?? 0) }}</strong>
                        </div>
                        <div class="price-row mb-3">
                            <span>@lang('Processing Time'):</span>
                            <strong>{{ $visaType->processing_time ?? '5-7 days' }}</strong>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <input type="hidden" name="type" value="visa">
                            
                            <div class="mb-3">
                                <label class="form-label">@lang('Full Name')</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">@lang('Email')</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">@lang('Phone')</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">@lang('Message')</label>
                                <textarea name="message" class="form-control" rows="3" placeholder="@lang('Please send me details about this visa type')"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                @lang('Request Information')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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
.visa-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.visa-section h3 {
    color: var(--primary);
    margin-bottom: 15px;
}
.apply-card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
}
.price-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}
</style>
@endpush
