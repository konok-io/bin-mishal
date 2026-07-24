@extends('layouts.admin')
@section('title', 'Leave Requests')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Leave Requests</h1>
    <a href="{{ route('admin.leave-requests.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Leave Request
    </a>
</div>
<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Leave Requests</h5>
        <span class="badge bg-primary">{{ $leaves->total() }} Total</span>
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
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $leave->employee?->user?->name ?? 'N/A' }}</td>
                        <td>{{ $leave->leave_type ?? 'N/A' }}</td>
                        <td>{{ $leave->start_date?->format('d M Y') ?? 'N/A' }}</td>
                        <td>{{ $leave->end_date?->format('d M Y') ?? 'N/A' }}</td>
                        <td>{{ $leave->total_days }} day(s)</td>
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
                        <td class="text-end">
                            @if($leave->status === 'pending')
                                <form action="{{ route('admin.leave-requests.approve', $leave->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal({{ $leave->id }})" title="Reject">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            @endif
                            <form action="{{ route('admin.leave-requests.destroy', $leave->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                            No leave requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $leaves->links() }}
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function showRejectModal(leaveId) {
    document.getElementById('rejectForm').action = '/admin/leave-requests/' + leaveId + '/reject';
    var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endsection
