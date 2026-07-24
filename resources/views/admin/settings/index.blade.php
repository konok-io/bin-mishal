@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-gear"></i> Settings</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Application Settings</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>App Name:</th><td>{{ config('app.name') }}</td></tr>
                    <tr><th>App URL:</th><td>{{ config('app.url') }}</td></tr>
                    <tr><th>Environment:</th><td>{{ config('app.env') }}</td></tr>
                    <tr><th>Debug Mode:</th><td>{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</td></tr>
                    <tr><th>Timezone:</th><td>{{ config('app.timezone') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Database Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Driver:</th><td>{{ config('database.default') }}</td></tr>
                    <tr><th>Database:</th><td>{{ config('database.connections.' . config('database.default') . '.database') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Quick Actions</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-people"></i> Manage Customers
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-ticket"></i> Manage Bookings
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.visas.index') }}" class="btn btn-outline-warning w-100">
                    <i class="bi bi-passport"></i> Manage Visas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
