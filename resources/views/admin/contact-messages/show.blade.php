@extends('layouts.admin')
@section('title', 'View Contact Message')
@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Contact Message</h1>
    <div class="d-flex gap-2">
        <form action="{{ route('contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
        <a href="{{ route('contact-messages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-muted mb-3">Contact Details</h5>
                <div class="mb-2">
                    <strong>Name:</strong> {{ $message->name }}
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> 
                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                </div>
                @if($message->phone)
                <div class="mb-2">
                    <strong>Phone:</strong> {{ $message->phone }}
                </div>
                @endif
                <div class="mb-2">
                    <strong>Subject:</strong> {{ $message->subject }}
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted mb-3">Status</h5>
                <div class="mb-2">
                    <span class="badge bg-{{ $message->is_read ? 'success' : 'warning' }}">
                        {{ $message->is_read ? 'Read' : 'Unread' }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Received:</strong> {{ $message->created_at->format('M d, Y h:i A') }}
                </div>
                @if($message->ip_address)
                <div class="mb-2">
                    <strong>IP Address:</strong> {{ $message->ip_address }}
                </div>
                @endif
            </div>
        </div>

        <hr>

        <h5 class="text-muted mb-3">Message</h5>
        <div class="p-3 bg-light rounded">
            {!! nl2br(e($message->message)) !!}
        </div>

        @if($message->is_read)
        <div class="mt-4">
            <a href="{{ route('contact-messages.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Messages
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
