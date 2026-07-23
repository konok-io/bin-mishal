@extends('admin.layouts.app')

@section('title', 'Package Types')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Package Types</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Package
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Dimensions</th>
                            <th>Max Weight</th>
                            <th>Base Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td><strong>{{ $package->code }}</strong></td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->dimensions }}</td>
                            <td>{{ $package->max_weight }} kg</td>
                            <td>SAR {{ number_format($package->base_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $package->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($package->cargos()->count() == 0)
                                <form action="{{ route('admin.cargo.packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $package->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.cargo.packages.update', $package->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Package</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" value="{{ $package->code }}" required></div>
                                                <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $package->name }}" required></div>
                                                <div class="col-md-4"><label class="form-label">Length (cm)</label><input type="number" name="length" class="form-control" value="{{ $package->length }}"></div>
                                                <div class="col-md-4"><label class="form-label">Width (cm)</label><input type="number" name="width" class="form-control" value="{{ $package->width }}"></div>
                                                <div class="col-md-4"><label class="form-label">Height (cm)</label><input type="number" name="height" class="form-control" value="{{ $package->height }}"></div>
                                                <div class="col-md-6"><label class="form-label">Max Weight (kg)</label><input type="number" name="max_weight" class="form-control" value="{{ $package->max_weight }}"></div>
                                                <div class="col-md-6"><label class="form-label">Base Price (SAR)</label><input type="number" name="base_price" class="form-control" value="{{ $package->base_price }}"></div>
                                                <div class="col-md-6"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ $package->sort_order }}"></div>
                                                <div class="col-md-6"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" {{ $package->is_active ? 'selected' : '' }}>Active</option><option value="0" {{ !$package->is_active ? 'selected' : '' }}>Inactive</option></select></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="7" class="text-center">No packages found</td></tr>
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
            <form action="{{ route('admin.cargo.packages.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Package</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Length (cm)</label><input type="number" name="length" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label">Width (cm)</label><input type="number" name="width" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label">Height (cm)</label><input type="number" name="height" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Max Weight (kg)</label><input type="number" name="max_weight" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Base Price (SAR)</label><input type="number" name="base_price" class="form-control"></div>
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
