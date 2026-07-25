@extends('layouts.admin')
@section('title', 'Maintenance Mode')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">Maintenance Mode Settings</h1>
</div>

<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Warning:</strong> When maintenance mode is enabled, the entire site will show a maintenance page to visitors.
    Admin users with the secret key can still access the site.
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Maintenance Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.maintenance.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="maintenance_enabled" name="maintenance_enabled" 
                                {{ $settings['maintenance_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="maintenance_enabled">
                                <strong>Enable Maintenance Mode</strong>
                            </label>
                        </div>
                        <small class="text-muted">When enabled, visitors will see a maintenance page</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Maintenance Message</label>
                        <textarea class="form-control" name="maintenance_message" rows="3" maxlength="500">{{ $settings['maintenance_message'] }}</textarea>
                        <small class="text-muted">This message will be shown to visitors during maintenance</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Allowed IP Addresses</label>
                        <textarea class="form-control" name="maintenance_allowed_ips" rows="2" 
                            placeholder="Enter IP addresses, one per line">{{ $settings['maintenance_allowed_ips'] }}</textarea>
                        <small class="text-muted">Visitors from these IPs can still access the site during maintenance (one per line)</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Status</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Maintenance Mode:</strong>
                    <span class="badge bg-{{ $settings['maintenance_enabled'] ? 'danger' : 'success' }}">
                        {{ $settings['maintenance_enabled'] ? 'ENABLED' : 'DISABLED' }}
                    </span>
                </p>
                <hr>
                <p class="small text-muted mb-0">
                    To access the site during maintenance, use the secret key:
                    <code>maintenance-secret</code>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
