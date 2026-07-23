{{-- Breadcrumb Component --}}

<nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
        <ol class="breadcrumb__list">
            @foreach($crumbs as $index => $crumb)
                <li class="breadcrumb__item{{ $loop->last ? ' is-active' : '' }}">
                    @if($crumb['url'] && !$loop->last)
                        <a href="{{ $crumb['url'] }}" class="breadcrumb__link">
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="breadcrumb__current">{{ $crumb['label'] }}</span>
                    @endif
                    
                    @if(!$loop->last)
                        <span class="breadcrumb__separator">
                            <x-heroicon-s-chevron-right class="w-4 h-4" />
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>

@push('styles')
<style>
.breadcrumb {
    padding: 1rem 0;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.breadcrumb__list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0.5rem;
}

.breadcrumb__item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.breadcrumb__link {
    color: #6b7280;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb__link:hover {
    color: var(--primary-color, #059669);
}

.breadcrumb__current {
    color: #374151;
    font-weight: 500;
}

.breadcrumb__separator {
    color: #9ca3af;
}

/* RTL Support */
[dir="rtl"] .breadcrumb__list {
    flex-direction: row-reverse;
}

[dir="rtl"] .breadcrumb__item {
    flex-direction: row-reverse;
}
</style>
@endpush
