@extends('admin.layouts.app')

@section('title', 'Zones - ' . $city->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Zones - {{ $city->name }}</h1>
            <a href="{{ route('admin.cargo.cities') }}" class="text-decoration-none">&larr; Back to Cities</a>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Zone
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Delivery Charge</th>
                            <th>Delivery Days</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zones as $zone)
                        <tr>
                            <td>{{ $zone->name }}</td>
                            <td>SAR {{ number_format($zone->delivery_charge, 2) }}</td>
                            <td>{{ $zone->min_delivery_days }} - {{ $zone->max_delivery_days }} days</td>
                            <td>
                                <span class="badge bg-{{ $zone->is_active ? 'success' : 'danger' }}">
                                    {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $zone->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.cargo.zones.destroy', $zone->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $zone->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.cargo.zones.update', $zone->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Zone</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $zone->name }}" required></div>
                                                <div class="col-md-6"><label class="form-label">Delivery Charge</label><input type="number" name="delivery_charge" class="form-control" value="{{ $zone->delivery_charge }}"></div>
                                                <div class="col-md-6"><label class="form-label">Min Delivery Days</label><input type="number" name="min_delivery_days" class="form-control" value="{{ $zone->min_delivery_days }}"></div>
                                                <div class="col-md-6"><label class="form-label">Max Delivery Days</label><input type="number" name="max_delivery_days" class="form-control" value="{{ $zone->max_delivery_days }}"></div>
                                                <div class="col-md-6"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ $zone->sort_order }}"></div>
                                                <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" {{ $zone->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$zone->is_active ? 'selected' : '' }}>Inactive</option></select></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="text-center">No zones found</td></tr>
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
            <form action="{{ route('admin.cargo.zones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="city_id" value="{{ $city->id }}">
                <div class="modal-header"><h5 class="modal-title">Add Zone</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Delivery Charge</label><input type="number" name="delivery_charge" class="form-control" value="0"></div>
                        <div class="col-md-6"><label class="form-label">Min Delivery Days</label><input type="number" name="min_delivery_days" class="form-control" value="1"></div>
                        <div class="col-md-6"><label class="form-label">Max Delivery Days</label><input type="number" name="max_delivery_days" class="form-control" value="3"></div>
                        <div class="col-md-6"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
                        <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
