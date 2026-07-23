{{-- Notice Ticker Section --}}
@php
    $notices = \App\Models\Notice::getActiveNotices();
@endphp

@if($notices->count() > 0)
<div class="notice-ticker bg-{{ $notices->first()->type === 'urgent' ? 'danger' : ($notices->first()->type === 'warning' ? 'warning' : 'primary') }} text-white py-2">
    <div class="container">
        <div class="d-flex align-items-center">
            <span class="badge bg-light text-dark me-3">
                <i class="fas fa-bullhorn me-1"></i>
                {{ __('common.notice') }}
            </span>
            <div class="ticker-wrapper flex-grow-1 overflow-hidden">
                <div class="ticker-content">
                    @foreach($notices as $notice)
                        <span class="ticker-item me-5">
                            @if($notice->link_url)
                                <a href="{{ $notice->link_url }}" class="text-white text-decoration-underline" target="_blank">
                                    {{ $notice->translated_content }}
                                </a>
                            @else
                                {{ $notice->translated_content }}
                            @endif
                        </span>
                    @endforeach
                    {{-- Duplicate for seamless loop --}}
                    @foreach($notices as $notice)
                        <span class="ticker-item me-5">
                            @if($notice->link_url)
                                <a href="{{ $notice->link_url }}" class="text-white text-decoration-underline" target="_blank">
                                    {{ $notice->translated_content }}
                                </a>
                            @else
                                {{ $notice->translated_content }}
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.notice-ticker {
    font-size: 14px;
}
.ticker-wrapper {
    position: relative;
    overflow: hidden;
}
.ticker-content {
    display: flex;
    animation: ticker {{ $notices->count() * 5 }}s linear infinite;
    white-space: nowrap;
}
.ticker-item {
    display: inline-block;
    flex-shrink: 0;
}
@keyframes ticker {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}
.notice-ticker:hover .ticker-content {
    animation-play-state: paused;
}
</style>
@endpush
@endif
