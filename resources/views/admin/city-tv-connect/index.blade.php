@extends('layouts.admin')
@section('title', 'City TV Connect - Branch Management')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 mb-1">City TV Connect - Branch Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">City TV Connect</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('city-tv-connect.create') }}" class="btn btn-admin-primary">
            <i class="fas fa-plus me-2"></i>Add Branch
        </a>
        <a href="{{ route('city-tv-connect.cameras') }}" class="btn btn-secondary ms-2" target="_blank">
            <i class="fas fa-video me-2"></i>Live Cameras
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="card-header-custom">
        <h5 class="mb-0"><i class="fas fa-satellite-dish me-2"></i>Connected Branches</h5>
    </div>
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>Branch Name</th>
                        <th>Serial Number</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Last Sync</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                    <tr>
                        <td>
                            <strong>{{ $branch->name }}</strong>
                            @if($branch->notes)
                                <br><small class="text-muted">{{ $branch->notes }}</small>
                            @endif
                        </td>
                        <td><code>{{ $branch->serial_number }}</code></td>
                        <td>{{ $branch->ip_address ?? 'N/A' }}:{{ $branch->port }}</td>
                        <td>
                            @if($branch->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($branch->status === 'error')
                                <span class="badge bg-danger">Error</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($branch->last_sync)
                                {{ $branch->last_sync->diffForHumans() }}
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('city-tv-connect.show', $branch->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('city-tv-connect.edit', $branch->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('city-tv-connect.destroy', $branch->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this branch?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-muted mb-0">No branches configured yet.</p>
                            <a href="{{ route('city-tv-connect.create') }}">Add your first branch</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $branches->links() }}
        </div>
    </div>
</div>
@endsection
