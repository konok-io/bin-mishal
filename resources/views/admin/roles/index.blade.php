@extends('layouts.admin')
@section('title', 'Roles & Permissions')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Roles & Permissions</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Role
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <h5 class="mb-0">All Roles</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Users Count</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ ucfirst($role->name) }}</strong>
                            @if(in_array($role->name, ['super_admin', 'admin', 'employee']))
                                <span class="badge bg-secondary ms-2">System</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary me-1">{{ $role->permissions->count() }} permissions</span>
                        </td>
                        <td>
                            {{ $role->users->count() }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($role->name !== 'super_admin')
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(!in_array($role->name, ['admin', 'employee']) && $role->users->count() === 0)
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No roles found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
