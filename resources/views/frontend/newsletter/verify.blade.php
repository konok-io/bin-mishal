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
                        <h2 class="mb-3">{{ __('Newsletter Verified') }}</h2>
                        <p class="text-muted">{{ $message }}</p>
                    @else
                        <div class="mb-4">
                            <i class="fas fa-exclamation-circle text-danger" style="font-size: 64px;"></i>
                        </div>
                        <h2 class="mb-3">{{ __('Verification Failed') }}</h2>
                        <p class="text-muted">{{ $message }}</p>
                    @endif
                    
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        {{ __('Go to Homepage') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
