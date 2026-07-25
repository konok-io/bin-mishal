@extends('layouts.admin')
@section('title', 'WhatsApp Broadcast')

@section('content')
<div class="admin-page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">WhatsApp Broadcast</h1>
        <a href="{{ route('admin.whatsapp-broadcast.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> New Broadcast
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Broadcast History</h5>
            </div>
            <div class="card-body">
                @if($broadcasts->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No broadcasts sent yet.</p>
                        <a href="{{ route('admin.whatsapp-broadcast.create') }}" class="btn btn-primary">
                            Create Your First Broadcast
                        </a>
                    </div>
                @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Message</th>
                                <th>Recipients</th>
                                <th>Status</th>
                                <th>Sent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($broadcasts as $broadcast)
                            <tr>
                                <td>#{{ $broadcast->id }}</td>
                                <td>{{ Str::limit($broadcast->message, 50) }}</td>
                                <td>{{ $broadcast->recipient_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $broadcast->status === 'sent' ? 'success' : 'warning' }}">
                                        {{ ucfirst($broadcast->status) }}
                                    </span>
                                </td>
                                <td>{{ $broadcast->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.whatsapp-broadcast.show', $broadcast->id) }}" class="btn btn-sm btn-outline-primary">
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
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Floating Button Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.whatsapp-broadcast.update-settings') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="whatsapp_enabled" name="whatsapp_enabled" 
                                {{ $settings['whatsapp_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="whatsapp_enabled">Enable WhatsApp Button</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control" name="whatsapp_number" 
                            value="{{ $settings['whatsapp_number'] }}" placeholder="+966501234567">
                        <small class="text-muted">With country code</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <select name="whatsapp_position" class="form-select">
                            <option value="left" {{ $settings['whatsapp_position'] == 'left' ? 'selected' : '' }}>Bottom Left</option>
                            <option value="right" {{ $settings['whatsapp_position'] == 'right' ? 'selected' : '' }}>Bottom Right</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default Message</label>
                        <textarea class="form-control" name="whatsapp_default_message" rows="2">{{ $settings['whatsapp_default_message'] }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>

        <div class="admin-card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Available Recipients</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <i class="bi bi-person me-1"></i> Staff Users: <strong>{{ $recipients['users'] }}</strong>
                </p>
                <p class="mb-0">
                    <i class="bi bi-people me-1"></i> Customers: <strong>{{ $recipients['customers'] }}</strong>
                </p>
            </div>
        </div>

        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>Note:</strong> WhatsApp Broadcast requires WhatsApp Business API credentials.
            <a href="{{ route('admin.integrations.index') }}">Configure here</a>.
        </div>
    </div>
</div>
@endsection
