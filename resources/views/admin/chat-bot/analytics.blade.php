@extends('layouts.admin')
@section('title', 'Chat Analytics')

@section('content')
<div class="admin-page-header">
    <h1 class="h4 mb-0">Chat Analytics</h1>
</div>

<div class="row mb-4">
    <div class="col-md-2">
        <div class="admin-card text-center">
            <div class="card-body">
                <h4 class="mb-0">{{ $analytics['total_conversations'] }}</h4>
                <small class="text-muted">Total Chats</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <div class="card-body">
                <h4 class="mb-0 text-success">{{ $analytics['ai_resolved'] }}</h4>
                <small class="text-muted">AI Resolved</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <div class="card-body">
                <h4 class="mb-0 text-warning">{{ $analytics['human_escalated'] }}</h4>
                <small class="text-muted">Human Escalated</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <div class="card-body">
                <h4 class="mb-0 text-secondary">{{ $analytics['abandoned'] }}</h4>
                <small class="text-muted">Abandoned</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <div class="card-body">
                <h4 class="mb-0">{{ $analytics['avg_response_time'] }}</h4>
                <small class="text-muted">Avg Response</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <div class="card-body">
                <h4 class="mb-0 text-primary">{{ $analytics['resolution_rate'] }}</h4>
                <small class="text-muted">Resolution Rate</small>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <h5 class="mb-0">Performance Metrics</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Chat analytics will be available once the AI Chat Assistant is active and receiving conversations.
            Configure the AI provider in <a href="{{ route('admin.chat-bot.index') }}">Chat Settings</a>.
        </div>
    </div>
</div>
@endsection
