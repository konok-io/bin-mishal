@extends('layouts.admin')
@section('title', 'Biometric Devices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-fingerprint"></i> Biometric Devices</h1>
    <a href="{{ route('admin.biometric-devices.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Device
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.biometric-devices.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, device ID, brand..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.biometric-devices.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Device ID</th>
                        <th>Brand</th>
                        <th>IP Address</th>
                        <th>Branch</th>
                        <th>Sync Method</th>
                        <th>Status</th>
                        <th>Last Sync</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $device)
                    <tr>
                        <td><strong>{{ $device->name }}</strong></td>
                        <td>{{ $device->device_id ?? 'N/A' }}</td>
                        <td>{{ $brands[$device->brand] ?? $device->brand }}</td>
                        <td>{{ $device->ip_address ? $device->ip_address . ':' . $device->port : 'N/A' }}</td>
                        <td>{{ $device->branch->name ?? 'N/A' }}</td>
                        <td>{{ $device->sync_method ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClass = match($device->status) {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'maintenance' => 'warning',
                                    'offline' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statuses[$device->status] ?? $device->status }}</span>
                        </td>
                        <td>
                            @if($device->last_sync_at)
                                <small>{{ $device->last_sync_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.biometric-devices.show', $device->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.biometric-devices.edit', $device->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.biometric-devices.destroy', $device->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No devices found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $devices->withQueryString()->links() }}
    </div>
</div>
@endsection
