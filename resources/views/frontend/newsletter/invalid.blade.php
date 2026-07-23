@extends('layouts.public')

@section('title', __('Newsletter'))

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <div class="error-icon mb-4">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h2 class="mb-3">@lang('Invalid Link')</h2>
                    <p class="text-muted">{{ $message ?? __('This link is invalid or has expired.') }}</p>
                    <a href="{{ route('home') }}" class="btn btn-success mt-3">
                        <i class="bi bi-house"></i> @lang('Go to Homepage')
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.error-icon {
    width: 100px;
    height: 100px;
    background: #fff3cd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.error-icon i {
    font-size: 4rem;
    color: #ffc107;
}
</style>
@endpush
