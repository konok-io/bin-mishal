@extends('layouts.admin')
@section('title', 'Payroll Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-wallet2"></i> Payroll Details</h1>
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Employee Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Employee ID:</strong> {{ $payroll->employee->employee_id ?? 'N/A' }}</p>
                        <p><strong>Name:</strong> {{ $payroll->employee->name ?? 'Unknown' }}</p>
                        <p><strong>Department:</strong> {{ $payroll->employee->department ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Period:</strong> {{ $payroll->period_start->format('M d, Y') }} - {{ $payroll->period_end->format('M d, Y') }}</p>
                        <p><strong>Status:</strong> 
                            @php
                                $statusClass = match($payroll->status) {
                                    'draft' => 'secondary',
                                    'processed' => 'info',
                                    'approved' => 'warning',
                                    'paid' => 'success',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $payroll->status }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Salary Breakdown</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><strong>Basic Salary</strong></td>
                            <td class="text-end">{{ number_format($payroll->basic_salary, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Allowances</strong></td>
                        </tr>
                        @php
                            $allowances = $payroll->allowances ?? [];
                            $totalAllowances = 0;
                        @endphp
                        @if(is_array($allowances) && count($allowances) > 0)
                            @foreach($allowances as $name => $amount)
                                <tr>
                                    <td style="padding-left: 30px;">{{ ucfirst($name) }}</td>
                                    <td class="text-end text-success">+ {{ number_format($amount, 2) }}</td>
                                </tr>
                                @php $totalAllowances += $amount; @endphp
                            @endforeach
                        @else
                            <tr><td colspan="2" class="text-muted">No allowances</td></tr>
                        @endif
                        @if($payroll->bonus > 0)
                        <tr>
                            <td style="padding-left: 30px;">Bonus</td>
                            <td class="text-end text-success">+ {{ number_format($payroll->bonus, 2) }}</td>
                        </tr>
                        @php $totalAllowances += $payroll->bonus; @endphp
                        @endif
                        <tr class="table-light">
                            <td><strong>Total Earnings</strong></td>
                            <td class="text-end"><strong>{{ number_format($payroll->basic_salary + $totalAllowances, 2) }}</strong></td>
                        </tr>
                        
                        <tr><td colspan="2"><strong>Deductions</strong></td></tr>
                        @php
                            $deductions = $payroll->deductions ?? [];
                            $totalDeductions = 0;
                        @endphp
                        @if(is_array($deductions) && count($deductions) > 0)
                            @foreach($deductions as $name => $amount)
                                <tr>
                                    <td style="padding-left: 30px;">{{ ucfirst($name) }}</td>
                                    <td class="text-end text-danger">- {{ number_format($amount, 2) }}</td>
                                </tr>
                                @php $totalDeductions += $amount; @endphp
                            @endforeach
                        @else
                            <tr><td colspan="2" class="text-muted">No deductions</td></tr>
                        @endif
                        @if($payroll->late_deduction > 0)
                        <tr>
                            <td style="padding-left: 30px;">Late Deduction ({{ $payroll->late_days }} days)</td>
                            <td class="text-end text-danger">- {{ number_format($payroll->late_deduction, 2) }}</td>
                        </tr>
                        @php $totalDeductions += $payroll->late_deduction; @endphp
                        @endif
                        <tr class="table-light">
                            <td><strong>Total Deductions</strong></td>
                            <td class="text-end"><strong class="text-danger">{{ number_format($totalDeductions, 2) }}</strong></td>
                        </tr>
                        
                        <tr class="table-success">
                            <td><strong>Net Salary</strong></td>
                            <td class="text-end"><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Payroll
                </a>
                @if($payroll->status !== 'paid')
                    <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this payroll record?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Processing Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Processed By:</strong> {{ $payroll->processor->name ?? 'N/A' }}</p>
                <p><strong>Processed At:</strong> {{ $payroll->processed_at ? $payroll->processed_at->format('M d, Y H:i') : 'N/A' }}</p>
                @if($payroll->paid_at)
                    <p><strong>Paid At:</strong> {{ $payroll->paid_at->format('M d, Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
