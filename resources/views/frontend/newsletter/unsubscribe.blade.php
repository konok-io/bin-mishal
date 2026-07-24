@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    @if($success)
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                        </div>
                        <h2 class="mb-3">{{ __('Unsubscribed') }}</h2>
                        <p class="text-muted">{{ __('You have been successfully unsubscribed from our newsletter. We\'re sorry to see you go!') }}</p>
                        <p class="small text-muted">{{ __('You can subscribe again anytime from our website.') }}</p>
                    @else
                        <div class="mb-4">
                            <i class="fas fa-exclamation-circle text-danger" style="font-size: 64px;"></i>
                        </div>
                        <h2 class="mb-3">{{ __('Unsubscribe Failed') }}</h2>
                        <p class="text-muted">{{ __('The unsubscribe link is invalid or has expired.') }}</p>
                    @endif
                    
                    <a href="{{ locale_route('home') }}" class="btn btn-primary mt-3">
                        {{ __('Go to Homepage') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
