@extends('layouts.admin')
@section('title', 'Menus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-list"></i> Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Menu
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('admin.menus.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search menus..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="location" class="form-select">
                    <option value="">All Locations</option>
                    @foreach($locations as $value => $label)
                        <option value="{{ $value }}" {{ request('location') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td><strong>{{ $menu->name }}</strong></td>
                        <td><span class="badge bg-info">{{ $locations[$menu->location] ?? $menu->location }}</span></td>
                        <td>{{ $menu->items->count() }}</td>
                        <td>
                            @if($menu->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $menu->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Manage Items
                            </a>
                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center text-muted py-4">No menus found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $menus->withQueryString()->links() }}
    </div>
</div>
@endsection
