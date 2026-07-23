@extends('layouts.admin')
@section('title', 'Visa Application')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-passport"></i> Application: {{ $application->application_no }}</h1>
    <a href="{{ route('admin.visas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Application Details</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="30%">Application No:</th>
                        <td><strong>{{ $application->application_no }}</strong></td>
                    </tr>
                    <tr>
                        <th>Customer:</th>
                        <td>{{ $application->customer->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Visa Type:</th>
                        <td>{{ $application->visaType->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Applicant Name:</th>
                        <td>{{ $application->applicant_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Passport No:</th>
                        <td>{{ $application->passport_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td>SAR {{ number_format($application->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @php
                                $statusClass = match($application->status) {
                                    'draft' => 'bg-secondary',
                                    'submitted' => 'bg-info',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-warning'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $application->status)) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $application->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Status Actions -->
        <div class="card mb-4">
            <div class="card-body">
                @if($application->status === 'draft')
                <form action="{{ route('admin.visas.submit', $application->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Submit Application
                    </button>
                </form>
                @elseif($application->status === 'government_processing')
                <form action="{{ route('admin.visas.approve', $application->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Approve
                    </button>
                </form>
                <form action="{{ route('admin.visas.reject', $application->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="text" name="reason" placeholder="Rejection reason" class="form-control d-inline-block w-auto" required>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Reject
                    </button>
                </form>
                @elseif($application->status === 'approved')
                <form action="{{ route('admin.visas.deliver', $application->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-truck"></i> Mark as Delivered
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
