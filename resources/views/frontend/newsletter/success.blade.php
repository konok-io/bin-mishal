@extends('layouts.public')

@section('title', __('Newsletter Subscription'))

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <div class="success-icon mb-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="mb-3">@lang('Subscription Verified!')</h2>
                    <p class="text-muted">{{ $message ?? __('Thank you! Your subscription has been verified.') }}</p>
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
.success-icon {
    width: 100px;
    height: 100px;
    background: #d4edda;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.success-icon i {
    font-size: 4rem;
    color: #28a745;
}
</style>
@endpush
