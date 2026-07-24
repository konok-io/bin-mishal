@extends('layouts.public')

@section('title', __('Newsletter'))

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    <div class="info-icon mb-4">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <h2 class="mb-3">@lang('Already Verified')</h2>
                    <p class="text-muted">{{ $message ?? __('Your email is already verified.') }}</p>
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
.info-icon {
    width: 100px;
    height: 100px;
    background: #cfe2ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.info-icon i {
    font-size: 4rem;
    color: #0d6efd;
}
</style>
@endpush
