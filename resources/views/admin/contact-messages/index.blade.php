@extends('layouts.admin')
@section('title', 'Contact Messages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-envelope-open"></i> Contact Messages</h1>
</div>

<div class="card">
    <div class="card-header">
        <form action="{{ route('contact-messages.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search messages..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('contact-messages.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr {{ !$message->is_read ? 'class=table-light' : '' }}>
                        <td><strong>{{ $message->name }}</strong></td>
                        <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                        <td>{{ Str::limit($message->subject, 40) ?: '-' }}</td>
                        <td>
                            @if($message->is_read)
                                <span class="badge bg-secondary">Read</span>
                            @else
                                <span class="badge bg-primary">Unread</span>
                            @endif
                        </td>
                        <td>{{ $message->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('contact-messages.show', $message->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('contact-messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No messages found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $messages->withQueryString()->links() }}
    </div>
</div>
@endsection
