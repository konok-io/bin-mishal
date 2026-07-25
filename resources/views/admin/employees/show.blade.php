@extends('layouts.admin')
@section('title', 'Employee Details')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Employee Details</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    {{-- Basic Information --}}
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Basic Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Name</th>
                        <td>{{ $employee->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $employee->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $employee->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $employee->status === 'active' ? 'success' : ($employee->status === 'inactive' ? 'warning' : 'danger') }}">
                                {{ ucfirst($employee->status ?? 'unknown') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td>{{ $employee->branch?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $employee->created_at ? $employee->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Employment Details --}}
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Employment Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Employee Code</th>
                        <td>{{ $employee->employee?->employee_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td>{{ $employee->employee?->designation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ $employee->employee?->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Joining Date</th>
                        <td>{{ $employee->employee?->joining_date?->format('Y-m-d') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Biometric ID</th>
                        <td>{{ $employee->employee?->biometric_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Employment Status</th>
                        <td>
                            <span class="badge bg-{{ ($employee->employee?->status ?? '') === 'active' ? 'success' : (($employee->employee?->status ?? '') === 'inactive' ? 'warning' : 'danger') }}">
                                {{ ucfirst($employee->employee?->status ?? 'unknown') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Salary Information --}}
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Salary Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Salary Type</th>
                        <td>{{ ucfirst($employee->employee?->salary_type ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>{{ $employee->employee?->salary_type === 'hourly' ? 'Hourly Rate' : 'Monthly Salary' }}</th>
                        <td>
                            @if($employee->employee?->salary_type === 'hourly')
                                SAR {{ number_format($employee->employee?->hourly_rate ?? 0, 2) }}/hr
                            @else
                                SAR {{ number_format($employee->employee?->salary ?? 0, 2) }}/month
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Documents & ID --}}
    <div class="col-md-6">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Documents & ID</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Iqama No</th>
                        <td>{{ $employee->employee?->iqama_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Passport No</th>
                        <td>{{ $employee->employee?->passport_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Emergency Contact</th>
                        <td>{{ $employee->employee?->emergency_contact ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Bank Account</th>
                        <td>{{ $employee->employee?->bank_account ? '********' . substr($employee->employee?->bank_account, -4) : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
