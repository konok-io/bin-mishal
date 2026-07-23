@extends('layouts.app')

@section('title', 'Leave Management - ' . __('app.app_name'))

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-airplane me-2"></i>Leave Management</h2>
            <p class="text-muted mb-0">Apply and track your leave requests</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('employee.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyLeaveModal">
                <i class="bi bi-plus-circle"></i> Apply Leave
            </button>
        </div>
    </div>

    <!-- Leave Balance -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check text-success fs-1"></i>
                    <h3 class="mt-2 mb-1">{{ $leaveBalance['annual'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Annual Leave</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-bandaid text-warning fs-1"></i>
                    <h3 class="mt-2 mb-1">{{ $leaveBalance['sick'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Sick Leave</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-event text-info fs-1"></i>
                    <h3 class="mt-2 mb-1">{{ $leaveBalance['unpaid'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Unpaid Leave</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">My Leave Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $leave)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $leave->leave_type === 'annual' ? 'success' : ($leave->leave_type === 'sick' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($leave->leave_type) }}
                                    </span>
                                </td>
                                <td>{{ date('d M Y', strtotime($leave->start_date)) }}</td>
                                <td>{{ date('d M Y', strtotime($leave->end_date)) }}</td>
                                <td>{{ $leave->days }} {{ Str::plural('day', $leave->days) }}</td>
                                <td>
                                    <small>{{ Str::limit($leave->reason ?? 'No reason provided', 30) }}</small>
                                </td>
                                <td>{{ date('d M Y', strtotime($leave->created_at)) }}</td>
                                <td>
                                    @if($leave->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($leave->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($leave->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="viewLeaveDetails({{ $leave->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($leave->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="cancelLeave({{ $leave->id }})">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No leave requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($leaveRequests->hasPages())
                <div class="mt-3">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Apply Leave Modal -->
<div class="modal fade" id="applyLeaveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-airplane me-2"></i>Apply for Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('employee.leave.apply') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Leave Type</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="">Select Leave Type</option>
                            <option value="annual">Annual Leave ({{ $leaveBalance['annual'] ?? 0 }} days available)</option>
                            <option value="sick">Sick Leave ({{ $leaveBalance['sick'] ?? 0 }} days available)</option>
                            <option value="unpaid">Unpaid Leave</option>
                            <option value="emergency">Emergency Leave</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Number of Days</label>
                        <input type="number" name="days" class="form-control" min="1" value="1" required>
                        <small class="text-muted">Leave will be deducted from your balance</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Please provide a reason for your leave..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function viewLeaveDetails(id) {
    // Open modal with leave details
    console.log('View leave:', id);
}

function cancelLeave(id) {
    if (confirm('Are you sure you want to cancel this leave request?')) {
        fetch('/employee/leave/' + id + '/cancel', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Calculate days when dates change
document.querySelector('input[name="end_date"]').addEventListener('change', function() {
    const startDate = new Date(document.querySelector('input[name="start_date"]').value);
    const endDate = new Date(this.value);
    
    if (startDate && endDate && endDate >= startDate) {
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        document.querySelector('input[name="days"]').value = diffDays;
    }
});
</script>
@endpush
