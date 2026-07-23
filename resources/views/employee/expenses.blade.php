@extends('employee.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-receipt me-2"></i>My Expense Claims</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newExpenseModal">
                    <i class="fas fa-plus me-2"></i>New Expense Claim
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Approved</h5>
                            <h3>{{ number_format($stats['approved'], 2) }} SAR</h3>
                            <small>{{ $stats['approved_count'] }} claims</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">Pending</h5>
                            <h3>{{ number_format($stats['pending'], 2) }} SAR</h3>
                            <small>{{ $stats['pending_count'] }} claims</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Applied to Payroll</h5>
                            <h3>{{ number_format($stats['applied'], 2) }} SAR</h3>
                            <small>{{ $stats['applied_count'] }} claims</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Rejected</h5>
                            <h3>{{ number_format($stats['rejected'], 2) }} SAR</h3>
                            <small>{{ $stats['rejected_count'] }} claims</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claims Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Expense Claims History</h5>
                </div>
                <div class="card-body">
                    @if($claims->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-receipt text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">No expense claims yet</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Claim #</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($claims as $claim)
                                    <tr>
                                        <td><strong>{{ $claim->claim_number }}</strong></td>
                                        <td>
                                            <span class="badge bg-primary">{{ $claim->expenseType->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $claim->expense_date->format('M d, Y') }}</td>
                                        <td>{{ Str::limit($claim->description, 50) }}</td>
                                        <td><strong>{{ number_format($claim->amount, 2) }} {{ $claim->currency }}</strong></td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'submitted' => 'info',
                                                    'manager_review' => 'warning',
                                                    'hr_review' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'paid' => 'success',
                                                    'applied_to_payroll' => 'primary',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$claim->status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewModal{{ $claim->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $claims->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Expense Modal -->
<div class="modal fade" id="newExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('employee.expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Expense Claim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Expense Type *</label>
                            <select name="expense_type_id" class="form-select" required>
                                <option value="">Select Type</option>
                                @foreach($expenseTypes as $type)
                                    <option value="{{ $type->id }}" data-payment="{{ $type->payment_type }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expense Date *</label>
                            <input type="date" name="expense_date" class="form-control" 
                                   value="{{ old('expense_date', now()->toDateString()) }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Amount *</label>
                            <div class="input-group">
                                <input type="number" name="amount" class="form-control" 
                                       step="0.01" min="0" placeholder="0.00" required>
                                <select name="currency" class="form-select" style="max-width: 100px;">
                                    <option value="SAR">SAR</option>
                                    <option value="BDT">BDT</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Describe your expense..." required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Receipt (Optional)</label>
                        <input type="file" name="receipt" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Max size: 5MB. Accepted: PDF, JPG, PNG</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Submit Claim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modals -->
@foreach($claims as $claim)
<div class="modal fade" id="viewModal{{ $claim->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Expense Claim Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Claim Number:</th>
                        <td>{{ $claim->claim_number }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td>{{ $claim->expenseType->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $claim->expense_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Amount:</th>
                        <td><strong>{{ number_format($claim->amount, 2) }} {{ $claim->currency }}</strong></td>
                    </tr>
                    <tr>
                        <th>Payment Type:</th>
                        <td>
                            @if($claim->payment_type === 'reimbursable')
                                <span class="badge bg-success">Reimbursable</span>
                            @else
                                <span class="badge bg-danger">Deductible</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @php
                                $statusColors = [
                                    'draft' => 'secondary',
                                    'submitted' => 'info',
                                    'manager_review' => 'warning',
                                    'hr_review' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'paid' => 'success',
                                    'applied_to_payroll' => 'primary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$claim->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $claim->description }}</td>
                    </tr>
                    @if($claim->rejection_reason)
                    <tr class="table-danger">
                        <th>Rejection Reason:</th>
                        <td>{{ $claim->rejection_reason }}</td>
                    </tr>
                    @endif
                    @if($claim->reviewed_at)
                    <tr>
                        <th>Reviewed:</th>
                        <td>{{ $claim->reviewed_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
