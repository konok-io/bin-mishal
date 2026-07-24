{{-- Public Menu Component --}}
{{-- Usage: <x-public.menu location="header" /> --}}

@php
    // Define default login URLs if not provided
    $adminLoginUrl = $adminLoginUrl ?? '/admin/login';
    $employeeLoginUrl = $employeeLoginUrl ?? '/bn/employee/login';
    $portalLoginUrl = $portalLoginUrl ?? '/bn/portal/login';
    $portalRegisterUrl = $portalRegisterUrl ?? '/bn/portal/register';
@endphp

@if(!empty($items))
    <ul class="menu menu--{{ $location }}">
        @foreach($items as $item)
            <li class="menu-item{{ !empty($item['children']) ? ' has-children' : '' }}{{ $item['is_active'] ? ' is-active' : '' }}">
                <a href="{{ $item['url'] ?? '#' }}"
                   target="{{ $item['target'] }}"
                   class="{{ $item['css_class'] }}"
                   @if($item['target'] === '_blank') rel="noopener noreferrer" @endif
                >
                    @if($item['icon'])
                        <span class="menu-icon">
                            @if(str_starts_with($item['icon'], 'heroicons'))
                                @php
                                    $iconParts = explode('.', $item['icon']);
                                    $iconType = $iconParts[1] ?? 'outline';
                                    $iconName = $iconParts[2] ?? '';
                                @endphp
                                @if($iconType === 'outline')
                                    <x-heroicon-o-{{ $iconName }} class="w-5 h-5" />
                                @else
                                    <x-heroicon-s-{{ $iconName }} class="w-5 h-5" />
                                @endif
                            @endif
                        </span>
                    @endif
                    <span class="menu-text">{{ $item['title'] }}</span>
                    @if($item['badge_text'])
                        <span class="menu-badge badge-{{ $item['badge_color'] ?? 'primary' }}">
                            {{ $item['badge_text'] }}
                        </span>
                    @endif
                    @if(!empty($item['children']))
                        <span class="menu-arrow">
                            <x-heroicon-s-chevron-down class="w-4 h-4" />
                        </span>
                    @endif
                </a>

                @if(!empty($item['children']))
                    <ul class="sub-menu">
                        @foreach($item['children'] as $child)
                            <li class="sub-menu-item{{ !empty($child['children']) ? ' has-children' : '' }}{{ $child['is_active'] ? ' is-active' : '' }}">
                                <a href="{{ $child['url'] ?? '#' }}"
                                   target="{{ $child['target'] }}"
                                   class="{{ $child['css_class'] }}"
                                >
                                    @if($child['icon'])
                                        <span class="menu-icon">
                                            <x-heroicon-o-{{ $child['icon'] }} class="w-4 h-4" />
                                        </span>
                                    @endif
                                    <span class="menu-text">{{ $child['title'] }}</span>
                                    @if($child['badge_text'])
                                        <span class="menu-badge badge-{{ $child['badge_color'] ?? 'primary' }}">
                                            {{ $child['badge_text'] }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach

        {{-- Dynamic User Menu (Dashboard or Login) --}}
        @if($loggedInUserType && $dashboardUrl)
            {{-- Show Dashboard menu when logged in --}}
            <li class="menu-item has-children">
                <a href="{{ $dashboardUrl }}" class="{{ request()->path() === ltrim($dashboardUrl, '/') ? 'is-active' : '' }}">
                    <span class="menu-icon">
                        <x-heroicon-s-user-circle class="w-5 h-5" />
                    </span>
                    <span class="menu-text">
                        @switch($loggedInUserType)
                            @case('admin')
                                @lang('app.admin_dashboard')
                            @case('employee')
                                @lang('app.employee_dashboard')
                            @case('customer')
                                @lang('app.my_dashboard')
                        @endswitch
                    </span>
                    <span class="menu-arrow">
                        <x-heroicon-s-chevron-down class="w-4 h-4" />
                    </span>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item">
                        <a href="{{ $dashboardUrl }}">
                            <span class="menu-text">
                                @switch($loggedInUserType)
                                    @case('admin')
                                        @lang('app.admin_dashboard')
                                    @case('employee')
                                        @lang('app.employee_dashboard')
                                    @case('customer')
                                        @lang('app.my_dashboard')
                                @endswitch
                            </span>
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                                <span>@lang('app.logout')</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        @else
            {{-- Show Login/Register menu when not logged in --}}
            <li class="menu-item has-children">
                <a href="#">
                    <span class="menu-icon">
                        <x-heroicon-s-user-circle class="w-5 h-5" />
                    </span>
                    <span class="menu-text">@lang('app.login')</span>
                    <span class="menu-arrow">
                        <x-heroicon-s-chevron-down class="w-4 h-4" />
                    </span>
                </a>
                <ul class="sub-menu">
                    <li class="sub-menu-item">
                        <a href="{{ $adminLoginUrl }}">
                            <span class="menu-text">@lang('app.admin_dashboard')</span>
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <a href="{{ $employeeLoginUrl }}">
                            <span class="menu-text">@lang('app.employee_dashboard')</span>
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <a href="{{ $portalLoginUrl }}">
                            <span class="menu-text">@lang('app.login') (@lang('app.customer'))</span>
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <a href="{{ $portalRegisterUrl }}">
                            <span class="menu-text">@lang('app.register')</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
@endif
