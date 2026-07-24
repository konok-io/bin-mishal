@extends('layouts.admin')
@section('title', 'Add Branch - City TV Connect')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 mb-1">Add New Branch</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.city-tv-connect.index') }}">City TV Connect</a></li>
                <li class="breadcrumb-item active">Add Branch</li>
            </ol>
        </nav>
    </div>
</div>

<div class="admin-card">
    <div class="card-header-custom">
        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Branch Information</h5>
    </div>
    <div class="card-body-custom">
        <form action="{{ route('admin.city-tv-connect.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required placeholder="e.g., Dhaka Main Office">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="error" {{ old('status') == 'error' ? 'selected' : '' }}>Error</option>
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
                           value="{{ old('serial_number') }}" required placeholder="e.g., CTV-DHAKA-001">
                    @error('serial_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label-admin">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           required placeholder="Enter device password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label-admin">Port</label>
                    <input type="number" name="port" class="form-control @error('port') is-invalid @enderror" 
                           value="{{ old('port', 8000) }}" placeholder="8000">
                    @error('port')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">IP Address</label>
                    <input type="text" name="ip_address" class="form-control @error('ip_address') is-invalid @enderror" 
                           value="{{ old('ip_address') }}" placeholder="e.g., 192.168.1.100">
                    @error('ip_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label-admin">Notes</label>
                    <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" 
                           value="{{ old('notes') }}" placeholder="Optional notes">
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="fas fa-save me-2"></i>Save Branch
                </button>
                <a href="{{ route('admin.city-tv-connect.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
