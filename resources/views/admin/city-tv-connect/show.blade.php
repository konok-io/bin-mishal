@extends('layouts.admin')
@section('title', 'Branch Details - City TV Connect')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 mb-1">{{ $cityTVConnect->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('city-tv-connect.index') }}">City TV Connect</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('city-tv-connect.edit', $cityTVConnect->id) }}" class="btn btn-admin-primary">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('city-tv-connect.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header-custom">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Branch Information</h5>
            </div>
            <div class="card-body-custom">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Branch Name</td>
                        <td><strong>{{ $cityTVConnect->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Serial Number</td>
                        <td><code>{{ $cityTVConnect->serial_number }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">IP Address</td>
                        <td>{{ $cityTVConnect->ip_address ?? 'N/A' }}:{{ $cityTVConnect->port }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($cityTVConnect->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($cityTVConnect->status === 'error')
                                <span class="badge bg-danger">Error</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Last Sync</td>
                        <td>{{ $cityTVConnect->last_sync ? $cityTVConnect->last_sync->format('d M Y, h:i A') : 'Never' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $cityTVConnect->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Updated</td>
                        <td>{{ $cityTVConnect->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header-custom">
                <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h5>
            </div>
            <div class="card-body-custom">
                <p class="mb-0">{{ $cityTVConnect->notes ?: 'No notes available.' }}</p>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="card-header-custom">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body-custom">
                <a href="{{ route('city-tv-connect.cameras') }}" class="btn btn-primary w-100 mb-2" target="_blank">
                    <i class="fas fa-video me-2"></i>View Live Cameras
                </a>
                <form action="{{ route('city-tv-connect.destroy', $cityTVConnect->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Delete this branch?')">
                        <i class="fas fa-trash me-2"></i>Delete Branch
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
