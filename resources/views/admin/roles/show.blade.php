@extends('layouts.admin')
@section('title', 'Role Details')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Role: {{ ucfirst($role->name) }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        @if($role->name !== 'super_admin')
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shield me-2"></i>Permissions</h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($role->permissions->sortBy('name') as $permission)
                            <span class="badge bg-primary">{{ $permission->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No permissions assigned.</p>
                @endif
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Users with this Role</h5>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4F2FE8&color=fff&size=32" 
                                                     class="rounded-circle" alt="">
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No users have this role.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0">Role Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th>Name</th>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <th>Guard</th>
                        <td>{{ $role->guard_name }}</td>
                    </tr>
                    <tr>
                        <th>Permissions</th>
                        <td>{{ $role->permissions->count() }}</td>
                    </tr>
                    <tr>
                        <th>Users</th>
                        <td>{{ $role->users->count() }}</td>
                    </tr>
                    <tr>
                        <th>System Role</th>
                        <td>
                            @if(in_array($role->name, ['super_admin', 'admin', 'employee']))
                                <span class="badge bg-secondary">Yes</span>
                            @else
                                <span class="badge bg-success">No</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($role->name !== 'super_admin' && !in_array($role->name, ['admin', 'employee']) && $role->users->count() === 0)
                    <hr>
                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Delete Role
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
