@extends('layouts.admin')
@section('title', 'Live Camera Feeds - City TV Connect')

@push('styles')
<style>
    .camera-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1rem;
    }
    .camera-card {
        background: #1a1a2e;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .camera-header {
        padding: 0.75rem 1rem;
        background: #16213e;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .camera-header h5 {
        margin: 0;
        font-size: 0.9rem;
        color: #fff;
    }
    .camera-header .badge {
        font-size: 0.7rem;
    }
    .camera-viewport {
        height: 240px;
        background: #0f0f23;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .camera-placeholder {
        color: #4a4a6a;
        text-align: center;
    }
    .camera-placeholder i {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    .camera-placeholder p {
        margin: 0;
        font-size: 0.85rem;
    }
    .camera-overlay {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .camera-overlay .time {
        background: rgba(0,0,0,0.6);
        color: #fff;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    .camera-overlay .signal {
        color: #00ff88;
        font-size: 0.8rem;
    }
    .camera-overlay .signal.offline {
        color: #ff4444;
    }
    .camera-footer {
        padding: 0.5rem 1rem;
        background: #16213e;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .camera-footer .ip {
        color: #888;
        font-size: 0.75rem;
        font-family: monospace;
    }
    .camera-footer .controls button {
        background: transparent;
        border: 1px solid #333;
        color: #888;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        margin-left: 0.25rem;
    }
    .camera-footer .controls button:hover {
        background: #333;
        color: #fff;
    }
    @media (max-width: 768px) {
        .camera-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 mb-1"><i class="fas fa-video text-danger me-2"></i>Live Camera Feeds</h1>
        <p class="text-muted mb-0 small">City TV Connect - All Branches Surveillance</p>
    </div>
    <div>
        <span class="badge bg-success me-2">
            <i class="fas fa-circle me-1" style="font-size: 0.5rem"></i>
            {{ $branches->where('status', 'active')->count() }} Online
        </span>
        <span class="badge bg-danger">
            <i class="fas fa-circle me-1" style="font-size: 0.5rem"></i>
            {{ $branches->where('status', '!=', 'active')->count() }} Offline
        </span>
    </div>
</div>

<div class="camera-grid">
    @forelse($branches as $branch)
    <div class="camera-card" data-branch-id="{{ $branch->id }}">
        <div class="camera-header">
            <h5>
                <i class="fas fa-building me-2"></i>
                {{ $branch->name }}
            </h5>
            @if($branch->status === 'active')
                <span class="badge bg-success">LIVE</span>
            @else
                <span class="badge bg-secondary">OFFLINE</span>
            @endif
        </div>
        <div class="camera-viewport" id="camera-{{ $branch->id }}">
            <div class="camera-placeholder">
                <i class="fas fa-video-slash"></i>
                <p>Camera Feed</p>
                <small>{{ $branch->serial_number }}</small>
            </div>
            <div class="camera-overlay">
                <span class="time" id="time-{{ $branch->id }}"></span>
                <span class="signal {{ $branch->status === 'active' ? '' : 'offline' }}">
                    <i class="fas fa-wifi me-1"></i>
                    {{ $branch->status === 'active' ? 'Connected' : 'Disconnected' }}
                </span>
            </div>
        </div>
        <div class="camera-footer">
            <span class="ip">{{ $branch->ip_address ?? 'N/A' }}:{{ $branch->port }}</span>
            <div class="controls">
                <button onclick="toggleFullscreen('camera-{{ $branch->id }}')">
                    <i class="fas fa-expand"></i>
                </button>
                <button onclick="refreshCamera({{ $branch->id }})">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            No branches configured. Add branches from the City TV Connect management page.
        </div>
    </div>
    @endforelse
</div>

@if($branches->count() > 0)
<div class="mt-4 text-center text-muted small">
    <p class="mb-1">Last updated: <span id="last-update">{{ now()->format('d M Y, h:i:s A') }}</span></p>
    <p class="mb-0">Auto-refresh: Every 30 seconds</p>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Update time displays
    function updateTimes() {
        document.querySelectorAll('[id^="time-"]').forEach(el => {
            el.textContent = new Date().toLocaleTimeString();
        });
        document.getElementById('last-update').textContent = new Date().toLocaleString();
    }
    updateTimes();
    setInterval(updateTimes, 1000);
    
    // Toggle fullscreen
    function toggleFullscreen(cameraId) {
        const el = document.getElementById(cameraId);
        if (el.requestFullscreen) {
            el.requestFullscreen();
        } else if (el.webkitRequestFullscreen) {
            el.webkitRequestFullscreen();
        }
    }
    
    // Refresh camera (placeholder for real integration)
    function refreshCamera(branchId) {
        const cameraEl = document.getElementById('camera-' + branchId);
        cameraEl.style.opacity = '0.5';
        setTimeout(() => {
            cameraEl.style.opacity = '1';
        }, 500);
    }
</script>
@endpush
