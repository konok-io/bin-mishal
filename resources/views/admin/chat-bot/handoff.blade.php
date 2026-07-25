@extends('layouts.admin')
@section('title', 'Human Handoff Queue')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">Human Handoff Queue</h1>
</div>

<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle me-2"></i>
    Conversations escalated from AI will appear here. Staff members can respond directly from this queue.
</div>

<div class="admin-card">
    <div class="card-header">
        <h5 class="mb-0">Pending Escalations</h5>
    </div>
    <div class="card-body">
        @if($pending->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No pending escalations!</p>
                <p class="small text-muted">All conversations have been handled.</p>
            </div>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Visitor</th>
                        <th>Contact</th>
                        <th>Last Message</th>
                        <th>Page</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $item)
                    <tr>
                        <td>{{ $item->created_at->diffForHumans() }}</td>
                        <td>{{ $item->visitor_name }}</td>
                        <td>
                            {{ $item->visitor_email ?? 'N/A' }}<br>
                            {{ $item->visitor_phone ?? '' }}
                        </td>
                        <td>{{ Str::limit($item->last_message, 50) }}</td>
                        <td>{{ Str::limit($item->page_url, 30) }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-success">
                                <i class="bi bi-reply"></i> Respond
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
