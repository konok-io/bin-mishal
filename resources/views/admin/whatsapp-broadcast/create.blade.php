@extends('layouts.admin')
@section('title', 'Compose WhatsApp Broadcast')

@section('content')
<div class="admin-page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Compose Broadcast</h1>
        <a href="{{ route('admin.whatsapp-broadcast.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-chat-square-text me-2"></i>Message</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.whatsapp-broadcast.send') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Recipients</label>
                        <select name="recipients" class="form-select" id="recipients">
                            <option value="all">All (Staff + Customers)</option>
                            <option value="all_users">All Staff Users</option>
                            <option value="all_customers">All Customers</option>
                            <option value="selected">Selected Individuals</option>
                        </select>
                    </div>

                    <div id="selected-recipients" class="d-none mb-3">
                        <label class="form-label">Select Recipients</label>
                        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <div class="mb-2">
                                <strong>Staff Users</strong>
                                @foreach($recipients['users'] as $user)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                                    <label class="form-check-label">{{ $user->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div>
                                <strong>Customers</strong>
                                @foreach($recipients['customers'] as $customer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="selected_customers[]" value="{{ $customer->id }}">
                                    <label class="form-check-label">{{ $customer->name ?? $customer->user->name ?? 'Customer #'.$customer->id }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="5" maxlength="1000" 
                            placeholder="Enter your broadcast message..." required></textarea>
                        <small class="text-muted">Maximum 1000 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Schedule (Optional)</label>
                        <input type="datetime-local" class="form-control" name="schedule">
                        <small class="text-muted">Leave empty to send immediately</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>WhatsApp Business API Required:</strong> This feature requires WhatsApp Business API credentials.
                        Configure in <a href="{{ route('admin.integrations.index') }}">Integrations</a>.
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send me-1"></i> Send Broadcast
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Tips</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li class="mb-2">Keep messages short and clear</li>
                    <li class="mb-2">Include a clear call-to-action</li>
                    <li class="mb-2">Test with a few recipients first</li>
                    <li>Respect business hours for best engagement</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('recipients').addEventListener('change', function() {
    const selected = document.getElementById('selected-recipients');
    if (this.value === 'selected') {
        selected.classList.remove('d-none');
    } else {
        selected.classList.add('d-none');
    }
});
</script>
@endpush
