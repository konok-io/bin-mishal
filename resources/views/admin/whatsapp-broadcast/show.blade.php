@extends('layouts.admin')
@section('title', 'Broadcast Details')

@section('content')
<div class="admin-page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Broadcast #{{ $broadcast['id'] }}</h1>
        <a href="{{ route('admin.whatsapp-broadcast.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0">Message</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $broadcast['message'] }}</p>
            </div>
        </div>

        <div class="admin-card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Delivery Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h4 class="text-success">{{ $broadcast['sent_count'] }}</h4>
                        <small class="text-muted">Delivered</small>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-danger">{{ $broadcast['failed_count'] }}</h4>
                        <small class="text-muted">Failed</small>
                    </div>
                    <div class="col-md-4">
                        <h4>{{ $broadcast['sent_count'] + $broadcast['failed_count'] }}</h4>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0">Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> <span class="badge bg-{{ $broadcast['status'] === 'sent' ? 'success' : 'warning' }}">{{ ucfirst($broadcast['status']) }}</span></p>
                <p><strong>Created:</strong> {{ $broadcast['created_at']->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
