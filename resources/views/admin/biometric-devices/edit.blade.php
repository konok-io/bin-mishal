@extends('layouts.admin')
@section('title', 'Edit Biometric Device')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-fingerprint"></i> Edit Biometric Device</h1>
    <a href="{{ route('biometric-devices.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('biometric-devices.update', $biometricDevice->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Device Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $biometricDevice->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="device_id" class="form-label">Device ID</label>
                    <input type="text" name="device_id" id="device_id" class="form-control @error('device_id') is-invalid @enderror" value="{{ old('device_id', $biometricDevice->device_id) }}">
                    @error('device_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <select name="brand" id="brand" class="form-select @error('brand') is-invalid @enderror" required>
                        @foreach($brands as $value => $label)
                            <option value="{{ $value }}" {{ old('brand', $biometricDevice->brand) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $biometricDevice->model) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="ip_address" class="form-label">IP Address</label>
                    <input type="text" name="ip_address" id="ip_address" class="form-control @error('ip_address') is-invalid @enderror" value="{{ old('ip_address', $biometricDevice->ip_address) }}" placeholder="192.168.1.100">
                    @error('ip_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="port" class="form-label">Port</label>
                    <input type="number" name="port" id="port" class="form-control" value="{{ old('port', $biometricDevice->port ?? 4370) }}" min="1" max="65535">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $biometricDevice->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="sync_method" class="form-label">Sync Method</label>
                    <select name="sync_method" id="sync_method" class="form-select @error('sync_method') is-invalid @enderror" required>
                        @foreach($syncMethods as $value => $label)
                            <option value="{{ $value }}" {{ old('sync_method', $biometricDevice->sync_method) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sync_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="sync_interval" class="form-label">Sync Interval (minutes)</label>
                    <input type="number" name="sync_interval" id="sync_interval" class="form-control" value="{{ old('sync_interval', $biometricDevice->sync_interval ?? 15) }}" min="5">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $biometricDevice->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="webhook_url" class="form-label">Webhook URL</label>
                    <input type="url" name="webhook_url" id="webhook_url" class="form-control" value="{{ old('webhook_url', $biometricDevice->webhook_url) }}" placeholder="https://example.com/webhook">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="api_key" class="form-label">API Key</label>
                    <input type="text" name="api_key" id="api_key" class="form-control" value="{{ old('api_key', $biometricDevice->api_key) }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="comm_key" class="form-label">Communication Key</label>
                <input type="text" name="comm_key" id="comm_key" class="form-control" value="{{ old('comm_key', $biometricDevice->comm_key) }}">
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $biometricDevice->notes) }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('biometric-devices.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Device</button>
            </div>
        </form>
    </div>
</div>
@endsection
