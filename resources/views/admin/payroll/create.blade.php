@extends('layouts.admin')
@section('title', 'Add Payroll')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-wallet2"></i> Add Payroll</h1>
    <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.payroll.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="employee_id" class="form-label">Employee</label>
                    <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->employee_id }} - {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="period_start" class="form-label">Period Start</label>
                    <input type="date" name="period_start" id="period_start" class="form-control @error('period_start') is-invalid @enderror" value="{{ old('period_start') }}" required>
                    @error('period_start')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="period_end" class="form-label">Period End</label>
                    <input type="date" name="period_end" id="period_end" class="form-control @error('period_end') is-invalid @enderror" value="{{ old('period_end') }}" required>
                    @error('period_end')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h5>Salary Details</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="basic_salary" class="form-label">Basic Salary</label>
                    <input type="number" name="basic_salary" id="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', 0) }}" step="0.01" min="0" required>
                    @error('basic_salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bonus" class="form-label">Bonus</label>
                    <input type="number" name="bonus" id="bonus" class="form-control" value="{{ old('bonus', 0) }}" step="0.01" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="late_days" class="form-label">Late Days</label>
                    <input type="number" name="late_days" id="late_days" class="form-control" value="{{ old('late_days', 0) }}" min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="late_deduction" class="form-label">Late Deduction</label>
                    <input type="number" name="late_deduction" id="late_deduction" class="form-control" value="{{ old('late_deduction', 0) }}" step="0.01" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', 'draft') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Allowances (JSON format)</label>
                <textarea name="allowances_json" id="allowances_json" class="form-control" rows="2" placeholder='{"transport": 500, "medical": 300}'>{{ old('allowances_json', '{}') }}</textarea>
                <small class="text-muted">Enter as JSON: {"name": amount}</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Deductions (JSON format)</label>
                <textarea name="deductions_json" id="deductions_json" class="form-control" rows="2" placeholder='{"tax": 200, "insurance": 150}'>{{ old('deductions_json', '{}') }}</textarea>
                <small class="text-muted">Enter as JSON: {"name": amount}</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Payroll</button>
            </div>
        </form>
    </div>
</div>
@endsection
