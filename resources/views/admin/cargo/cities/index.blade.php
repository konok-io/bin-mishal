@extends('admin.layouts.app')

@section('title', 'Cities & Zones')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cities & Zones</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add City
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Zones</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                        <tr>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->code ?? '-' }}</td>
                            <td>
                                @if($city->is_saudi)<span class="badge bg-success">Saudi</span>@endif
                                @if($city->is_bangladesh)<span class="badge bg-info">Bangladesh</span>@endif
                            </td>
                            <td>{{ $city->zones->count() }}</td>
                            <td>
                                <span class="badge bg-{{ $city->is_active ? 'success' : 'danger' }}">
                                    {{ $city->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.cargo.zones', $city->id) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-map-marker-alt"></i> Zones
                                </a>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $city->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($city->zones->count() == 0)
                                <form action="{{ route('admin.cargo.cities.destroy', $city->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $city->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.cargo.cities.update', $city->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit City</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $city->name }}" required></div>
                                                <div class="col-md-6"><label class="form-label">Code</label><input type="text" name="code" class="form-control" value="{{ $city->code }}"></div>
                                                <div class="col-md-4"><div class="form-check"><input type="checkbox" name="is_saudi" class="form-check-input" {{ $city->is_saudi ? 'checked' : '' }}><label class="form-check-label">Saudi City</label></div></div>
                                                <div class="col-md-4"><div class="form-check"><input type="checkbox" name="is_bangladesh" class="form-check-input" {{ $city->is_bangladesh ? 'checked' : '' }}><label class="form-check-label">Bangladesh</label></div></div>
                                                <div class="col-md-4"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" {{ $city->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$city->is_active ? 'selected' : '' }}>Inactive</option></select></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="6" class="text-center">No cities found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cargo.cities.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add City</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Code</label><input type="text" name="code" class="form-control"></div>
                        <div class="col-md-4"><div class="form-check"><input type="checkbox" name="is_saudi" class="form-check-input" value="1"><label class="form-check-label">Saudi City</label></div></div>
                        <div class="col-md-4"><div class="form-check"><input type="checkbox" name="is_bangladesh" class="form-check-input" value="1"><label class="form-check-label">Bangladesh</label></div></div>
                        <div class="col-md-4"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
