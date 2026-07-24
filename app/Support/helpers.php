<?php

declare(strict_types=1);

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('route_exists')) {
    function route_exists(string $name): bool
    {
        return app('router')->has($name);
    }
}

if (!function_exists('locale_config')) {
    function locale_config(?string $locale = null): ?array
    {
        $locale = $locale ?? app()->getLocale();
        return config("locales.enabled.{$locale}");
    }
}

if (!function_exists('is_rtl')) {
    function is_rtl(?string $locale = null): bool
    {
        $config = locale_config($locale);
        return ($config['direction'] ?? 'ltr') === 'rtl';
    }
}

if (!function_exists('locale_route')) {
function locale_route(string $name, array $params = [], ?string $locale = null): string
    {
        // Use provided locale, or try app(), or use hardcoded 'bn' as final fallback
        if ($locale === null || $locale === '') {
            try {
                $locale = app()->getLocale();
            } catch (\Throwable $e) {
                $locale = null;
            }
        }
        
        // Final fallback to 'bn'
        if (empty($locale)) {
            $locale = 'bn';
        }
        
        $params['locale'] = $locale;

        return route($name, $params);
    }
}

if (!function_exists('switch_locale_url')) {
    function switch_locale_url(string $targetLocale, ?string $path = null): string
    {
        $path = $path ?? request()->path();
        $path = trim($path, '/');
        $segments = explode('/', $path);
        if (in_array($segments[0] ?? '', ['bn', 'en', 'ar'])) {
            array_shift($segments);
        }
        $newPath = $targetLocale . '/' . implode('/', $segments);
        return url($newPath);
    }
}

if (!function_exists('localize')) {
    function localize(string $path): string
    {
        $locale = app()->getLocale();
        return url("{$locale}/{$path}");
    }
}

if (!function_exists('getLocalizedURL')) {
    function getLocalizedURL(string $url, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';
        $path = preg_replace('#^/(bn|en|ar)/#', '/', $path);
        return url("/{$locale}{$path}");
    }
}
