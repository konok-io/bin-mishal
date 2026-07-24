@extends('layouts.admin')
@section('title', 'Edit Branch - City TV Connect')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 mb-1">Edit Branch</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.city-tv-connect.index') }}">City TV Connect</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="admin-card">
    <div class="card-header-custom">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Branch Information</h5>
    </div>
    <div class="card-body-custom">
        <form action="{{ route('admin.city-tv-connect.update', $cityTVConnect->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $cityTVConnect->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $cityTVConnect->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $cityTVConnect->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="error" {{ old('status', $cityTVConnect->status) == 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label-admin">Serial Number <span class="text-danger">*</span></label>
                    <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" 
                           value="{{ old('serial_number', $cityTVConnect->serial_number) }}" required>
                    @error('serial_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label-admin">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter new password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label-admin">Port</label>
                    <input type="number" name="port" class="form-control @error('port') is-invalid @enderror" 
                           value="{{ old('port', $cityTVConnect->port) }}">
                    @error('port')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">IP Address</label>
                    <input type="text" name="ip_address" class="form-control @error('ip_address') is-invalid @enderror" 
                           value="{{ old('ip_address', $cityTVConnect->ip_address) }}">
                    @error('ip_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">Notes</label>
                    <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" 
                           value="{{ old('notes', $cityTVConnect->notes) }}">
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="fas fa-save me-2"></i>Update Branch
                </button>
                <a href="{{ route('admin.city-tv-connect.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
