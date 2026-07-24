@extends('layouts.admin')
@section('title', 'Attendance')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Attendance</h1>
    <a href="{{ route('attendance.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Record Attendance
    </a>
</div>
<div class="admin-card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label for="employee_id" class="form-label">Employee</label>
                <select class="form-select" id="employee_id" name="employee_id">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user?->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                    <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                    <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Employee Code</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->employee?->user?->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->employee?->employee_code ?? 'N/A' }}</td>
                        <td>{{ $attendance->date?->format('d M Y') ?? 'N/A' }}</td>
                        <td>{{ $attendance->check_in ?? '-' }}</td>
                        <td>{{ $attendance->check_out ?? '-' }}</td>
                        <td>
                            @switch($attendance->status)
                                @case('present')
                                    <span class="badge bg-success">Present</span>
                                    @break
                                @case('absent')
                                    <span class="badge bg-danger">Absent</span>
                                    @break
                                @case('late')
                                    <span class="badge bg-warning">Late</span>
                                    @break
                                @case('half_day')
                                    <span class="badge bg-info">Half Day</span>
                                    @break
                                @case('leave')
                                    <span class="badge bg-secondary">Leave</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($attendance->status) }}</span>
                            @endswitch
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" onclick="editAttendance({{ $attendance->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No attendance records found for this date.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendances->withQueryString()->links() }}
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_check_in" class="form-label">Check In</label>
                                <input type="time" class="form-control" id="edit_check_in" name="check_in">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="edit_check_out" class="form-label">Check Out</label>
                                <input type="time" class="form-control" id="edit_check_out" name="check_out">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function editAttendance(id) {
    document.getElementById('editForm').action = '/admin/attendance/' + id;
    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>
@endsection
