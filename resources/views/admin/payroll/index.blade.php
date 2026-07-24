@extends('layouts.admin')
@section('title', 'Payroll')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-wallet2"></i> Payroll</h1>
    <a href="{{ route('payroll.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Payroll
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('payroll.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by employee..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="employee_id" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->employee_id }} - {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="month" name="month" class="form-control" value="{{ request('month') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Processed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                    <tr>
                        <td>
                            <strong>{{ $payroll->employee->employee_id ?? 'N/A' }}</strong><br>
                            <small>{{ $payroll->employee->name ?? 'Unknown' }}</small>
                        </td>
                        <td>
                            {{ $payroll->period_start->format('M d, Y') }} - {{ $payroll->period_end->format('M d, Y') }}
                        </td>
                        <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>
                            @php
                                $totalAllowances = is_array($payroll->allowances) ? array_sum($payroll->allowances) : 0;
                            @endphp
                            {{ number_format($totalAllowances + $payroll->bonus, 2) }}
                        </td>
                        <td>
                            @php
                                $totalDeductions = is_array($payroll->deductions) ? array_sum($payroll->deductions) : 0;
                            @endphp
                            {{ number_format($totalDeductions + $payroll->late_deduction, 2) }}
                        </td>
                        <td><strong class="text-success">{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        <td>
                            @php
                                $statusClass = match($payroll->status) {
                                    'draft' => 'secondary',
                                    'processed' => 'info',
                                    'approved' => 'warning',
                                    'paid' => 'success',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statuses[$payroll->status] ?? $payroll->status }}</span>
                        </td>
                        <td>
                            @if($payroll->processed_at)
                                <small>{{ $payroll->processed_at->format('M d, Y') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No payroll records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $payrolls->withQueryString()->links() }}
    </div>
</div>
@endsection
