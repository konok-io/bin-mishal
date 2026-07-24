@extends('layouts.admin')
@section('title', 'Edit Employee')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Edit Employee</h1>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>
<div class="admin-card">
    <div class="card-body">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Basic Information</h6>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Employment Details</h6>
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">Employee Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('employee_code') is-invalid @enderror" id="employee_code" name="employee_code" value="{{ old('employee_code', $employee->employee?->employee_code ?? '') }}" required>
                        @error('employee_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('designation') is-invalid @enderror" id="designation" name="designation" value="{{ old('designation', $employee->employee?->designation ?? '') }}" required>
                        @error('designation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department', $employee->employee?->department ?? '') }}">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="joining_date" class="form-label">Joining Date</label>
                        <input type="date" class="form-control @error('joining_date') is-invalid @enderror" id="joining_date" name="joining_date" value="{{ old('joining_date', $employee->employee?->joining_date?->format('Y-m-d') ?? '') }}">
                        @error('joining_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="biometric_id" class="form-label">Biometric ID <small class="text-muted">(Fingerprint ID)</small></label>
                        <input type="text" class="form-control @error('biometric_id') is-invalid @enderror" id="biometric_id" name="biometric_id" value="{{ old('biometric_id', $employee->employee?->biometric_id ?? '') }}" placeholder="e.g., BIO-001">
                        @error('biometric_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Unique ID for fingerprint/biometric attendance system</small>
                    </div>
                </div>
            </div>
            
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Salary Information</h6>
                    <div class="mb-3">
                        <label class="form-label">Salary Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="salary_type" id="salary_monthly" value="monthly" {{ old('salary_type', $employee->employee?->salary_type ?? 'monthly') === 'monthly' ? 'checked' : '' }} onchange="toggleSalaryFields()">
                                <label class="form-check-label" for="salary_monthly">Monthly</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="salary_type" id="salary_hourly" value="hourly" {{ old('salary_type', $employee->employee?->salary_type ?? '') === 'hourly' ? 'checked' : '' }} onchange="toggleSalaryFields()">
                                <label class="form-check-label" for="salary_hourly">Hourly</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="monthly_salary_group">
                        <label for="salary" class="form-label">Monthly Salary (SAR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary', $employee->employee?->salary ?? 0) }}">
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 d-none" id="hourly_rate_group">
                        <label for="hourly_rate" class="form-label">Hourly Rate (SAR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $employee->employee?->hourly_rate ?? 0) }}">
                        @error('hourly_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Documents & Status</h6>
                    <div class="mb-3">
                        <label for="iqama_no" class="form-label">Iqama No</label>
                        <input type="text" class="form-control @error('iqama_no') is-invalid @enderror" id="iqama_no" name="iqama_no" value="{{ old('iqama_no', $employee->employee?->iqama_no ?? '') }}">
                        @error('iqama_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="passport_no" class="form-label">Passport No</label>
                        <input type="text" class="form-control @error('passport_no') is-invalid @enderror" id="passport_no" name="passport_no" value="{{ old('passport_no', $employee->employee?->passport_no ?? '') }}">
                        @error('passport_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="active" {{ old('status', $employee->employee?->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->employee?->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="terminated" {{ old('status', $employee->employee?->status ?? '') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Employee
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function toggleSalaryFields() {
    const monthlyRadio = document.getElementById('salary_monthly');
    const monthlyGroup = document.getElementById('monthly_salary_group');
    const hourlyGroup = document.getElementById('hourly_rate_group');
    
    if (monthlyRadio.checked) {
        monthlyGroup.classList.remove('d-none');
        hourlyGroup.classList.add('d-none');
    } else {
        monthlyGroup.classList.add('d-none');
        hourlyGroup.classList.remove('d-none');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', toggleSalaryFields);
</script>
@endsection
