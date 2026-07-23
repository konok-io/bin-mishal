@extends('layouts.app')

@section('title', 'My Attendance - ' . __('app.app_name'))

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-calendar-check me-2"></i>My Attendance</h2>
            <p class="text-muted mb-0">Track your daily attendance records</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('employee.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Today's Status -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Today's Attendance</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <i class="bi bi-box-arrow-in-right text-success fs-1"></i>
                        <h5 class="mt-2 mb-1">{{ $todayCheckIn ?? '--:--' }}</h5>
                        <small class="text-muted">Check In</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <i class="bi bi-box-arrow-right text-danger fs-1"></i>
                        <h5 class="mt-2 mb-1">{{ $todayCheckOut ?? '--:--' }}</h5>
                        <small class="text-muted">Check Out</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded {{ ($todayStatus === 'Present') ? 'bg-success text-white' : 'bg-warning' }}">
                        <i class="bi bi-{{ $todayStatus === 'Present' ? 'check-circle' : 'clock' }} fs-1"></i>
                        <h5 class="mt-2 mb-1">{{ $todayStatus ?? 'Not Marked' }}</h5>
                        <small>{{ $todayHours ?? 0 }} hours worked</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Present Days</h6>
                    <h2 class="mb-0">{{ $monthlyStats['present'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Absent Days</h6>
                    <h2 class="mb-0">{{ $monthlyStats['absent'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h6 class="card-title">Late Arrivals</h6>
                    <h2 class="mb-0">{{ $monthlyStats['late'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="card-title">Working Hours</h6>
                    <h2 class="mb-0">{{ $monthlyStats['hours'] ?? 0 }}h</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Attendance History</h5>
            <form method="GET" class="d-flex gap-2">
                <select name="month" class="form-select form-select-sm" style="width: auto;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="form-select form-select-sm" style="width: auto;">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Working Hours</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceRecords as $record)
                            <tr>
                                <td>
                                    <strong>{{ date('d M Y', strtotime($record->date)) }}</strong>
                                </td>
                                <td>{{ date('l', strtotime($record->date)) }}</td>
                                <td>
                                    @if($record->check_in)
                                        <span class="{{ $record->is_late ? 'text-danger' : 'text-success' }}">
                                            {{ date('H:i', strtotime($record->check_in)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">--:--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->check_out)
                                        {{ date('H:i', strtotime($record->check_out)) }}
                                    @else
                                        <span class="text-muted">--:--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->working_hours)
                                        {{ number_format($record->working_hours, 1) }}h
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>
                                    @if($record->status === 'present')
                                        <span class="badge bg-success">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span class="badge bg-danger">Absent</span>
                                    @elseif($record->status === 'leave')
                                        <span class="badge bg-info">On Leave</span>
                                    @elseif($record->status === 'holiday')
                                        <span class="badge bg-secondary">Holiday</span>
                                    @else
                                        <span class="badge bg-warning">Late</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $record->remarks ?? '--' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No attendance records found for this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($attendanceRecords->hasPages())
                <div class="mt-3">
                    {{ $attendanceRecords->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
