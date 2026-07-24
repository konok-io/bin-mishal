@extends('layouts.admin')
@section('title', 'Edit Package')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-pencil"></i> Edit Umrah Package</h1>
    <a href="{{ route('admin.umrah.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.umrah.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ $package->title }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $package->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $package->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Price (Double)</label>
                    <input type="number" name="price_double" class="form-control" value="{{ $package->price_double }}" step="0.01">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $package->description }}</textarea>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Update Package
            </button>
        </form>
    </div>
</div>
@endsection
