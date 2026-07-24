@extends('admin.layouts.app')

@section('title', 'Cargo Types')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cargo Types</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Add Type
        </button>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Name (BN)</th>
                            <th>Name (AR)</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                        <tr>
                            <td><i class="{{ $type->icon ?? 'fas fa-box' }}"></i></td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->name_bn }}</td>
                            <td>{{ $type->name_ar }}</td>
                            <td>{{ $type->sort_order }}</td>
                            <td>
                                <span class="badge bg-{{ $type->is_active ? 'success' : 'danger' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $type->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($type->cargos()->count() == 0)
                                <form action="{{ route('admin.cargo.types.destroy', $type->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $type->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.cargo.types.update', $type->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Type</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Name (Bengali)</label>
                                                <input type="text" name="name_bn" class="form-control" value="{{ $type->name_bn }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Name (Arabic)</label>
                                                <input type="text" name="name_ar" class="form-control" value="{{ $type->name_ar }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Icon (FontAwesome)</label>
                                                <input type="text" name="icon" class="form-control" value="{{ $type->icon }}" placeholder="fas fa-box">
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Sort Order</label>
                                                    <input type="number" name="sort_order" class="form-control" value="{{ $type->sort_order }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <select name="is_active" class="form-select">
                                                        <option value="1" {{ $type->is_active ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ !$type->is_active ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="7" class="text-center">No types found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.cargo.types.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Cargo Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (Bengali)</label>
                        <input type="text" name="name_bn" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (Arabic)</label>
                        <input type="text" name="name_ar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (FontAwesome)</label>
                        <input type="text" name="icon" class="form-control" placeholder="fas fa-box">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
