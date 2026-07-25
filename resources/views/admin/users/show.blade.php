@extends('layouts.admin')
@section('title', 'User Details')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">User Details</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>User Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4F2FE8&color=fff&size=128" 
                         class="rounded-circle mb-3" alt="">
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Phone</th>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td>{{ $user->branch?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Login</th>
                        <td>
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('Y-m-d H:i') }}
                                <small class="text-muted">({{ $user->last_login_at->diffForHumans() }})</small>
                            @else
                                Never logged in
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shield me-2"></i>Permissions</h5>
            </div>
            <div class="card-body">
                @if($user->getAllPermissions()->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($user->getAllPermissions() as $permission)
                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No specific permissions assigned. Permissions are inherited from role.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
