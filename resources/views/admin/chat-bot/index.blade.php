@extends('layouts.admin')
@section('title', 'AI Chat Assistant')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">AI Chat Assistant Settings</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-robot me-2"></i>General Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.chat-bot.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="chat_enabled" name="chat_enabled" 
                                {{ $settings['chat_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="chat_enabled">
                                <strong>Enable AI Chat Assistant</strong>
                            </label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Position</label>
                            <select name="chat_position" class="form-select">
                                <option value="left" {{ $settings['chat_position'] == 'left' ? 'selected' : '' }}>Bottom Left</option>
                                <option value="right" {{ $settings['chat_position'] == 'right' ? 'selected' : '' }}>Bottom Right</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">AI Provider</label>
                            <select name="chat_ai_provider" class="form-select">
                                <option value="none" {{ $settings['chat_ai_provider'] == 'none' ? 'selected' : '' }}>Disabled (Demo Mode)</option>
                                <option value="openai" {{ $settings['chat_ai_provider'] == 'openai' ? 'selected' : '' }}>OpenAI (GPT)</option>
                                <option value="anthropic" {{ $settings['chat_ai_provider'] == 'anthropic' ? 'selected' : '' }}>Anthropic (Claude)</option>
                            </select>
                            <small class="text-muted">Configure API key in Integrations settings</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Greeting Message</label>
                        <input type="text" class="form-control" name="chat_greeting" 
                            value="{{ $settings['chat_greeting'] }}" maxlength="500">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Offline Message</label>
                        <textarea class="form-control" name="chat_offline_message" rows="2" maxlength="500">{{ $settings['chat_offline_message'] }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Business Hours Start</label>
                            <input type="time" class="form-control" name="chat_business_hours_start" 
                                value="{{ $settings['chat_business_hours_start'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Business Hours End</label>
                            <input type="time" class="form-control" name="chat_business_hours_end" 
                                value="{{ $settings['chat_business_hours_end'] }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="chat_lead_capture_enabled" 
                                name="chat_lead_capture_enabled" {{ $settings['chat_lead_capture_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="chat_lead_capture_enabled">
                                Enable Lead Capture (collect name/email/phone)
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="chat_human_handoff_enabled" 
                                name="chat_human_handoff_enabled" {{ $settings['chat_human_handoff_enabled'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="chat_human_handoff_enabled">
                                Enable Human Handoff (escalate to staff)
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Knowledge Base (Additional Instructions)</label>
                        <textarea class="form-control" name="chat_knowledge_base" rows="4" 
                            placeholder="Additional context for the AI assistant...">{{ $settings['chat_knowledge_base'] }}</textarea>
                        <small class="text-muted">This text will be used as system instructions for the AI.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Quick Links</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.chat-bot.conversations') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-chat-dots me-1"></i> View Conversations
                </a>
                <a href="{{ route('admin.chat-bot.analytics') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-graph-up me-1"></i> Chat Analytics
                </a>
                <a href="{{ route('admin.chat-bot.handoff') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-person-plus me-1"></i> Human Handoff Queue
                </a>
            </div>
        </div>

        <div class="admin-card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Status</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>AI Provider:</strong> 
                    <span class="badge bg-{{ $settings['chat_ai_provider'] !== 'none' ? 'success' : 'secondary' }}">
                        {{ ucfirst($settings['chat_ai_provider']) }}
                    </span>
                </p>
                <p class="mb-2">
                    <strong>API Key:</strong>
                    @if(env('OPENAI_API_KEY') || env('ANTHROPIC_API_KEY'))
                        <span class="badge bg-success">Configured</span>
                    @else
                        <span class="badge bg-warning">Not Set</span>
                    @endif
                </p>
                <a href="{{ route('admin.integrations.index') }}" class="small">
                    <i class="bi bi-plug me-1"></i> Configure in Integrations
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
