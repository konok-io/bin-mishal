@extends('layouts.admin')
@section('title', 'Biometric Device Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-fingerprint"></i> {{ $biometricDevice->name }}</h1>
    <a href="{{ route('biometric-devices.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Device Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Device ID:</strong> {{ $biometricDevice->device_id ?? 'N/A' }}</p>
                        <p><strong>Brand:</strong> {{ $biometricDevice->brand ?? 'N/A' }}</p>
                        <p><strong>Model:</strong> {{ $biometricDevice->model ?? 'N/A' }}</p>
                        <p><strong>Branch:</strong> {{ $biometricDevice->branch->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>IP Address:</strong> {{ $biometricDevice->ip_address ? $biometricDevice->ip_address . ':' . $biometricDevice->port : 'N/A' }}</p>
                        <p><strong>Sync Method:</strong> {{ $biometricDevice->sync_method ?? 'N/A' }}</p>
                        <p><strong>Sync Interval:</strong> {{ $biometricDevice->sync_interval ? $biometricDevice->sync_interval . ' minutes' : 'N/A' }}</p>
                        <p>
                            <strong>Status:</strong>
                            @php
                                $statusClass = match($biometricDevice->status) {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'maintenance' => 'warning',
                                    'offline' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $biometricDevice->status }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($biometricDevice->webhook_url || $biometricDevice->api_key || $biometricDevice->comm_key)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Connection Details</h5>
            </div>
            <div class="card-body">
                @if($biometricDevice->webhook_url)
                    <p><strong>Webhook URL:</strong> <code>{{ $biometricDevice->webhook_url }}</code></p>
                @endif
                @if($biometricDevice->api_key)
                    <p><strong>API Key:</strong> <code>{{ Str::mask($biometricDevice->api_key, '*', 0, strlen($biometricDevice->api_key) - 8) }}</code></p>
                @endif
                @if($biometricDevice->comm_key)
                    <p><strong>Comm Key:</strong> <code>{{ Str::mask($biometricDevice->comm_key, '*', 0, strlen($biometricDevice->comm_key) - 4) }}</code></p>
                @endif
            </div>
        </div>
        @endif

        @if($biometricDevice->notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Notes</h5>
            </div>
            <div class="card-body">
                <p>{{ $biometricDevice->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sync Status</h5>
            </div>
            <div class="card-body">
                <p><strong>Last Sync:</strong>
                    @if($biometricDevice->last_sync_at)
                        {{ $biometricDevice->last_sync_at->format('M d, Y H:i') }}
                        <br><small class="text-muted">({{ $biometricDevice->last_sync_at->diffForHumans() }})</small>
                    @else
                        <span class="text-muted">Never synced</span>
                    @endif
                </p>
                <p><strong>Needs Sync:</strong>
                    @if($biometricDevice->needsSync())
                        <span class="badge bg-warning">Yes</span>
                    @else
                        <span class="badge bg-success">No</span>
                    @endif
                </p>
                <p><strong>Is Online:</strong>
                    @if($biometricDevice->isOnline())
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('biometric-devices.edit', $biometricDevice->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Device
                </a>
                <form action="{{ route('biometric-devices.destroy', $biometricDevice->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this device?')">
                        <i class="bi bi-trash"></i> Delete Device
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
