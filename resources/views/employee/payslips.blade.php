@extends('layouts.app')

@section('title', 'My Payslips - ' . __('app.app_name'))

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-receipt me-2"></i>My Payslips</h2>
            <p class="text-muted mb-0">View and download your salary slips</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ locale_route('employee.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($payslips && $payslips->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Period</th>
                                <th>Gross Salary</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th>Status</th>
                                <th>Pay Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payslips as $payroll)
                                <tr>
                                    <td>
                                        <strong>{{ date('F', mktime(0, 0, 0, $payroll->payroll_month, 1)) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payroll->payroll_year }}</small>
                                    </td>
                                    <td class="text-success">
                                        {{ number_format($payroll->gross_salary ?? 0, 2) }} SAR
                                    </td>
                                    <td class="text-danger">
                                        -{{ number_format($payroll->total_deductions ?? 0, 2) }} SAR
                                    </td>
                                    <td class="fw-bold">
                                        {{ number_format($payroll->net_salary ?? 0, 2) }} SAR
                                    </td>
                                    <td>
                                        @if($payroll->status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($payroll->status === 'approved')
                                            <span class="badge bg-info">Approved</span>
                                        @elseif($payroll->status === 'reviewed')
                                            <span class="badge bg-warning">Reviewed</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payroll->pay_date)
                                            {{ date('d M Y', strtotime($payroll->pay_date)) }}
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($payroll->status === 'paid' || $payroll->status === 'approved')
                                            <a href="{{ locale_route('employee.payslip.download', $payroll->id) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="bi bi-download"></i> PDF
                                            </a>
                                        @else
                                            <span class="text-muted small">Not available yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $payslips->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">No Payslips Available</h4>
                    <p class="text-muted">Your payslips will appear here once they are processed.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Cards -->
    @if($payslips && $payslips->count() > 0)
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Earnings (YTD)</h6>
                    <h3 class="mb-0">{{ number_format($yearlyEarnings ?? 0, 2) }} SAR</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Total Deductions (YTD)</h6>
                    <h3 class="mb-0">{{ number_format($yearlyDeductions ?? 0, 2) }} SAR</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Net Salary (YTD)</h6>
                    <h3 class="mb-0">{{ number_format($yearlyNet ?? 0, 2) }} SAR</h3>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
