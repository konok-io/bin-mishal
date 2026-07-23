@extends('layouts.admin')
@section('title', 'Leads')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person-lines-fill"></i> Leads</h1>
    <a href="{{ route('admin.leads.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Lead
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Interest</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Follow Up</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->phone ?? '-' }}</td>
                    <td>{{ $lead->email ?? '-' }}</td>
                    <td>{{ $lead->service_interest ?? '-' }}</td>
                    <td>{{ $lead->source ?? '-' }}</td>
                    <td>
                        @php
                            $statusClass = match($lead->status) {
                                'new' => 'bg-primary',
                                'contacted' => 'bg-info',
                                'qualified' => 'bg-success',
                                'converted' => 'bg-success',
                                'lost' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ ucfirst($lead->status) }}</span>
                    </td>
                    <td>{{ $lead->follow_up_date?->format('d M') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.leads.show', $lead->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No leads found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $leads->withQueryString()->links() }}
    </div>
</div>
@endsection
