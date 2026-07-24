@extends('layouts.public')

@section('title', $slug . ' - ' . config('app.name'))

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ locale_route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ locale_route('labour-law') }}">@lang('Labour Law')</a></li>
                <li class="breadcrumb-item active">{{ $slug }}</li>
            </ol>
        </nav>
        
        <div class="law-content">
            <h1 class="mb-4">{{ ucwords(str_replace('-', ' ', $slug)) }}</h1>
            <p>@lang('Detailed information about this topic coming soon.')</p>
            <a href="{{ locale_route('labour-law') }}" class="btn btn-success mt-3">@lang('Back to Labour Law')</a>
        </div>
    </div>
</section>

@push('styles')
<style>
.law-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
</style>
@endpush

@endsection
