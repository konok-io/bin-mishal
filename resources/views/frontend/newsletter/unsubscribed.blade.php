@extends('layouts.public')

@section('title', __('Newsletter Unsubscribed'))

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <div class="bye-icon mb-4">
                        <i class="bi bi-envelope-x-fill"></i>
                    </div>
                    <h2 class="mb-3">@lang('Unsubscribed')</h2>
                    <p class="text-muted">{{ $message ?? __('You have been unsubscribed from our newsletter.') }}</p>
                    <p class="text-muted small">@lang("We're sorry to see you go. You can subscribe again anytime.")</p>
                    <a href="{{ locale_route('home') }}" class="btn btn-success mt-3">
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
.bye-icon {
    width: 100px;
    height: 100px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.bye-icon i {
    font-size: 4rem;
    color: #6c757d;
}
</style>
@endpush
