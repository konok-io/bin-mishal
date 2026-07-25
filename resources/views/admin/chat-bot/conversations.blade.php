@extends('layouts.admin')
@section('title', 'Chat Conversations')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">Chat Conversations</h1>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="card-body">
                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="card-body">
                <h3 class="mb-0 text-success">{{ $stats['resolved'] }}</h3>
                <small class="text-muted">AI Resolved</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="card-body">
                <h3 class="mb-0 text-warning">{{ $stats['escalated'] }}</h3>
                <small class="text-muted">Escalated</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <div class="card-body">
                <h3 class="mb-0 text-info">{{ $stats['pending'] }}</h3>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Conversations</h5>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-secondary active">All</button>
            <button class="btn btn-sm btn-outline-secondary">Resolved</button>
            <button class="btn btn-sm btn-outline-secondary">Escalated</button>
            <button class="btn btn-sm btn-outline-secondary">Pending</button>
        </div>
    </div>
    <div class="card-body">
        @if($conversations->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">No conversations yet.</p>
                <p class="small text-muted">Conversations will appear here once the chat widget is active on the website.</p>
            </div>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Visitor</th>
                        <th>Page</th>
                        <th>Status</th>
                        <th>Started</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conversations as $conv)
                    <tr>
                        <td>#{{ $conv->id }}</td>
                        <td>{{ $conv->visitor_name ?? 'Guest' }}</td>
                        <td>{{ $conv->page_url }}</td>
                        <td>
                            <span class="badge bg-{{ $conv->status === 'resolved' ? 'success' : ($conv->status === 'escalated' ? 'warning' : 'info') }}">
                                {{ ucfirst($conv->status) }}
                            </span>
                        </td>
                        <td>{{ $conv->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.chat-bot.conversations.show', $conv->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
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
