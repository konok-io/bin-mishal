@extends('layouts.admin')
@section('title', 'Lead Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> Lead: {{ $lead->name }}</h1>
    <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Lead Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Name:</th><td>{{ $lead->name }}</td></tr>
                    <tr><th>Phone:</th><td>{{ $lead->phone ?? '-' }}</td></tr>
                    <tr><th>WhatsApp:</th><td>{{ $lead->whatsapp ?? '-' }}</td></tr>
                    <tr><th>Email:</th><td>{{ $lead->email ?? '-' }}</td></tr>
                    <tr><th>Service Interest:</th><td>{{ $lead->service_interest ?? '-' }}</td></tr>
                    <tr><th>Source:</th><td>{{ $lead->source ?? '-' }}</td></tr>
                    <tr><th>Status:</th><td><span class="badge bg-primary">{{ ucfirst($lead->status) }}</span></td></tr>
                    <tr><th>Follow Up:</th><td>{{ $lead->follow_up_date?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><th>Assigned To:</th><td>{{ $lead->assignedTo->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Activities</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Type</th><th>Description</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($lead->activities as $activity)
                        <tr>
                            <td>{{ $activity->activity_type }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->created_at->format('d M') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No activities</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($lead->status !== 'converted')
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Add Activity</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.leads.addActivity', $lead->id) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="activity_type" class="form-control" placeholder="Activity type" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="description" class="form-control" placeholder="Description" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add Activity</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
