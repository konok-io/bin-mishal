@extends('layouts.admin')
@section('title', 'Employees')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Employee
    </a>
</div>
<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Employee List</h5>
        <span class="badge bg-primary">{{ $employees->total() }} Total</span>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Branch</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $employee->employee->employee_code ?? 'N/A' }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone ?? 'N/A' }}</td>
                        <td>{{ $employee->employee->designation ?? 'N/A' }}</td>
                        <td>{{ $employee->branch->name ?? 'N/A' }}</td>
                        <td>
                            @if($employee->employee)
                                @if($employee->employee->salary_type === 'hourly')
                                    SAR {{ number_format($employee->employee->hourly_rate, 2) }}/hr
                                @else
                                    SAR {{ number_format($employee->employee->salary, 2) }}/month
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($employee->employee)
                                <span class="badge bg-{{ $employee->employee->status === 'active' ? 'success' : ($employee->employee->status === 'inactive' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($employee->employee->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            No employees found. <a href="{{ route('employees.create') }}">Add your first employee</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $employees->links() }}
    </div>
</div>
@endsection
