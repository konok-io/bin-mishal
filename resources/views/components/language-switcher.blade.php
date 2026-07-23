{{-- Language Switcher Component --}}
{{-- Usage: <x-language-switcher /> --}}
<div class="language-switcher" style="display: flex; align-items: center; gap: 8px;">
    @foreach(enabled_locales() as $code => $config)
        <a
            href="{{ switch_locale_url($code) }}"
            style="
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                border-radius: 4px;
                text-decoration: none;
                font-size: 13px;
                border: 1px solid #ddd;
                color: #333;
                background: {{ $code === current_locale() ? '#f0f0f0' : '#fff' }};
                font-weight: {{ $code === current_locale() ? '600' : '400' }};
            "
            aria-current="{{ $code === current_locale() ? 'true' : 'false' }}"
        >
            <span>{{ $config['flag'] }}</span>
            <span>{{ $config['native_name'] }}</span>
            <span style="font-size: 11px; color: #888;">({{ $code }})</span>
        </a>
    @endforeach
</div>
