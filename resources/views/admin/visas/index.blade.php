@extends('layouts.admin')
@section('title', 'Visa Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-passport"></i> Visa Applications</h1>
    <a href="{{ route('visas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Application
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('visas.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Application No</th>
                    <th>Customer</th>
                    <th>Visa Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td><strong>{{ $app->application_no }}</strong></td>
                    <td>{{ $app->customer->user->name ?? 'N/A' }}</td>
                    <td>{{ $app->visaType->name ?? 'N/A' }}</td>
                    <td>SAR {{ number_format($app->total_amount, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($app->status->value) {
                                'draft' => 'bg-secondary',
                                'submitted' => 'bg-info',
                                'document_pending' => 'bg-warning',
                                'under_review' => 'bg-primary',
                                'government_processing' => 'bg-info',
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger',
                                'delivered' => 'bg-success',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $app->status->label() }}</span>
                    </td>
                    <td>{{ $app->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('visas.show', $app->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No applications found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $applications->withQueryString()->links() }}
    </div>
</div>
@endsection
