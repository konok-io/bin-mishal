@extends('layouts.app')

@section('title', 'Employee Dashboard - ' . __('app.app_name'))

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-muted mb-0">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ locale_route('employee.profile') }}" class="btn btn-outline-primary">
                <i class="bi bi-person"></i> My Profile
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="card-title">{{ auth()->user()->name }}</h5>
                    @if(auth()->user()->employee)
                        <p class="text-muted mb-1">{{ auth()->user()->employee->designation ?? 'Employee' }}</p>
                        <p class="text-muted small mb-3">{{ auth()->user()->employee->department ?? '' }}</p>
                        <div class="d-flex justify-content-center gap-3 small text-muted">
                            <span><i class="bi bi-person-badge"></i> {{ auth()->user()->employee->employee_id ?? 'N/A' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-8">
            <div class="row g-3">
                <!-- Attendance Today -->
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 small opacity-75">Today's Status</p>
                                    <h4 class="mb-0">{{ $todayStatus ?? 'Not Checked In' }}</h4>
                                </div>
                                <i class="bi bi-clock-history fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave Balance -->
                <div class="col-md-4">
                    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 small opacity-75">Leave Balance</p>
                                    <h4 class="mb-0">{{ $leaveBalance ?? 0 }} days</h4>
                                </div>
                                <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Payslip -->
                <div class="col-md-4">
                    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 small opacity-75">Latest Payslip</p>
                                    <h4 class="mb-0">{{ number_format($latestPayslip ?? 0, 0) }} SAR</h4>
                                </div>
                                <i class="bi bi-wallet2 fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Attendance Summary -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Attendance This Month</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h3 mb-1 text-success">{{ $attendanceStats['present'] ?? 0 }}</div>
                            <small class="text-muted">Present</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-1 text-danger">{{ $attendanceStats['absent'] ?? 0 }}</div>
                            <small class="text-muted">Absent</small>
                        </div>
                        <div class="col-4">
                            <div class="h3 mb-1 text-warning">{{ $attendanceStats['late'] ?? 0 }}</div>
                            <small class="text-muted">Late</small>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Working days: {{ $attendanceStats['total'] ?? 0 }}</span>
                        <a href="{{ locale_route('employee.attendance') }}" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Requests -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-airplane me-2"></i>Recent Leave Requests</h5>
                    <a href="{{ locale_route('employee.leave') }}" class="btn btn-sm btn-outline-primary">Apply Leave</a>
                </div>
                <div class="card-body">
                    @forelse($recentLeaves ?? [] as $leave)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <strong>{{ $leave->leave_type }}</strong>
                                <br>
                                <small class="text-muted">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3 mb-0">No recent leave requests</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Latest Payslip -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Latest Payslip</h5>
                    <a href="{{ locale_route('employee.payslips') }}" class="btn btn-sm btn-outline-primary">All Payslips</a>
                </div>
                <div class="card-body">
                    @if($latestPayslipRecord)
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 text-success">{{ number_format($latestPayslipRecord->net_salary, 2) }} SAR</h4>
                                <small class="text-muted">
                                    {{ date('F Y', mktime(0, 0, 0, $latestPayslipRecord->payroll_month, 1)) }}
                                </small>
                            </div>
                            <a href="{{ locale_route('employee.payslip.download', $latestPayslipRecord->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">No payslips available yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Notifications</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($notifications ?? [] as $notification)
                        <div class="p-3 border-bottom">
                            <p class="mb-1">{{ $notification->data['message'] ?? 'New notification' }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3 mb-0">No new notifications</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-folder2-open me-2"></i>My Documents</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($documents ?? [] as $doc)
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-file-earmark-text text-primary fs-1"></i>
                                    <p class="mb-1 mt-2 small fw-bold">{{ $doc->title ?? 'Document' }}</p>
                                    <small class="text-muted">{{ $doc->created_at->format('M d, Y') }}</small>
                                    <a href="{{ Storage::url($doc->file_path) }}" class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-3">
                                No documents available
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
