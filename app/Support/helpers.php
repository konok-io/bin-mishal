<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Laravel Locale Helpers
|--------------------------------------------------------------------------
|
| Helper functions for working with locales, translations, and localized URLs.
| These functions are autoloaded via composer.json "files" directive.
|
*/

if (!function_exists('current_locale')) {
    /**
     * Get the current locale code.
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('route_exists')) {
    /**
     * Check if a named route exists.
     */
    function route_exists(string $name): bool
    {
        return app('router')->has($name);
    }
}

if (!function_exists('locale_config')) {
    /**
     * Get the full config for the current or specified locale.
     */
    function locale_config(?string $locale = null): ?array
    {
        $locale = $locale ?? app()->getLocale();
        return config("locales.enabled.{$locale}");
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if the current locale is RTL.
     */
    function is_rtl(?string $locale = null): bool
    {
        $config = locale_config($locale);
        return ($config['direction'] ?? 'ltr') === 'rtl';
    }
}

if (!function_exists('locale_route')) {
    /**
     * Generate a localized URL for a named route.
     *
     * @param string $name Route name
     * @param array $params Route parameters
     * @param string|null $locale Target locale (null = current)
     */
    function locale_route(string $name, array $params = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $params = array_merge(['locale' => $locale], $params);
        
        // Check if route exists with the name
        if (app('router')->has($name)) {
            return route($name, $params);
        }
        
        return '#';
    }
}

if (!function_exists('switch_locale_url')) {
    /**
     * Get the same URL but with a different locale.
     *
     * @param string $targetLocale Target locale
     * @param string|null $path Override path (null = current)
     * @param string|null $queryString Preserve query string
     */
    function switch_locale_url(string $targetLocale, ?string $path = null, ?string $queryString = null): string
    {
        $path = $path ?? request()->path();
        $query = $queryString ?? request()->getQueryString();

        // Remove current locale prefix if present (handles /bn, /bn/, /bn/foo)
        $path = ltrim(preg_replace('/^(bn|en|ar)(\/|$)/', '', $path), '/');

        $url = $path ? "/{$targetLocale}/{$path}" : "/{$targetLocale}";

        if ($query) {
            $url .= '?' . $query;
        }

        return $url;
    }
}

if (!function_exists('bn_number')) {
    /**
     * Convert numbers to Bengali numerals.
     *
     * @param int|float|string $value
     * @return string
     */
    function bn_number($value): string
    {
        if (app()->getLocale() !== 'bn') {
            return (string) $value;
        }
        
        $bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $latinDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($latinDigits, $bengaliDigits, (string) $value);
    }
}

if (!function_exists('ar_number')) {
    /**
     * Convert numbers to Arabic numerals.
     *
     * @param int|float|string $value
     * @return string
     */
    function ar_number($value): string
    {
        if (app()->getLocale() !== 'ar') {
            return (string) $value;
        }
        
        $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $latinDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($latinDigits, $arabicDigits, (string) $value);
    }
}

if (!function_exists('localized_date')) {
    /**
     * Format a date according to the current locale's date format.
     *
     * @param mixed $date
     * @param string|null $format Override format
     * @return string
     */
    function localized_date($date, ?string $format = null): string
    {
        $date = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        $format = $format ?? (locale_config()['date_format'] ?? 'd M Y');
        
        return $date->format($format);
    }
}

if (!function_exists('money')) {
    /**
     * Format a monetary amount with currency symbol.
     *
     * @param float|int $amount
     * @param string $currency
     * @param bool $showSymbol
     * @return string
     */
    function money($amount, string $currency = 'SAR', bool $showSymbol = true): string
    {
        $locale = app()->getLocale();
        
        $symbols = [
            'SAR' => ['bn' => 'ر.س', 'en' => 'SAR', 'ar' => 'ر.س'],
            'BDT' => ['bn' => '৳', 'en' => '৳', 'ar' => '৳'],
            'USD' => ['bn' => '$', 'en' => '$', 'ar' => '$'],
        ];
        
        $symbol = $symbols[$currency][$locale] ?? $currency;
        $amount = number_format((float) $amount, 2);
        
        // Convert to locale-specific numbers if needed
        if ($locale === 'bn') {
            $amount = bn_number($amount);
            $symbol = $symbol; // Already Bengali
        } elseif ($locale === 'ar') {
            $amount = ar_number($amount);
            $symbol = $symbol; // Already Arabic
        }
        
        return $showSymbol ? "{$symbol} {$amount}" : $amount;
    }
}

if (!function_exists('number_format_localized')) {
    /**
     * Format a number according to locale conventions.
     *
     * @param float|int $value
     * @param int $decimals
     * @return string
     */
    function number_format_localized($value, int $decimals = 0): string
    {
        $locale = app()->getLocale();
        $number = number_format((float) $value, $decimals);
        
        if ($locale === 'bn') {
            return bn_number($number);
        } elseif ($locale === 'ar') {
            return ar_number($number);
        }
        
        return $number;
    }
}

if (!function_exists('enabled_locales')) {
    /**
     * Get all enabled locales.
     *
     * @return array
     */
    function enabled_locales(): array
    {
        return array_filter(
            config('locales.enabled', []),
            fn($locale) => $locale['enabled'] ?? false
        );
    }
}

if (!function_exists('locale_flag')) {
    /**
     * Get the flag emoji for a locale.
     *
     * @param string|null $locale
     * @return string
     */
    function locale_flag(?string $locale = null): string
    {
        $config = locale_config($locale);
        return $config['flag'] ?? '🌐';
    }
}

if (!function_exists('locale_native_name')) {
    /**
     * Get the native name for a locale.
     *
     * @param string|null $locale
     * @return string
     */
    function locale_native_name(?string $locale = null): string
    {
        $config = locale_config($locale);
        return $config['native_name'] ?? $locale ?? '';
    }
}

if (!function_exists('get_direction')) {
    /**
     * Get the text direction for a locale.
     *
     * @param string|null $locale
     * @return string
     */
    function get_direction(?string $locale = null): string
    {
        $config = locale_config($locale);
        return $config['direction'] ?? 'ltr';
    }
}

/*
|--------------------------------------------------------------------------
| CMS Helper Functions
|--------------------------------------------------------------------------
*/

if (!function_exists('setting')) {
    /**
     * Get a setting value from the database.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::getValue($key, $default);
    }
}

if (!function_exists('cms_setting')) {
    /**
     * Get a CMS-specific setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function cms_setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\CMS\Setting::getValue($key, $default);
    }
}

if (!function_exists('current_url_path')) {
    /**
     * Get the current URL path without locale prefix.
     *
     * @return string
     */
    function current_url_path(): string
    {
        $path = request()->path();
        return preg_replace('/^(bn|en|ar)\//', '', $path);
    }
}

/*
|--------------------------------------------------------------------------
| Contact Info Helper Functions
|--------------------------------------------------------------------------
*/

if (!function_exists('settings')) {
    /**
     * Alias for setting() - Get a setting value from the database.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function settings(string $key, mixed $default = null): mixed
    {
        return setting($key, $default);
    }
}

if (!function_exists('get_contact_info')) {
    /**
     * Get all contact information as array.
     *
     * @return array
     */
    function get_contact_info(): array
    {
        return [
            'email' => setting('contact_email', 'info@binmishal.com'),
            'phone' => setting('contact_phone', '+966 XX XXX XXXX'),
            'whatsapp' => setting('contact_whatsapp', '+966 XX XXX XXXX'),
            'address' => setting('contact_address', 'Saudi Arabia'),
            'working_hours' => setting('working_hours', 'Sat-Thu: 9AM-6PM'),
        ];
    }
}

if (!function_exists('get_whatsapp_link')) {
    /**
     * Generate WhatsApp chat link.
     *
     * @param string|null $phone
     * @param string $message
     * @return string
     */
    function get_whatsapp_link(?string $phone = null, string $message = 'Hello!'): string
    {
        $phone = $phone ?? setting('contact_whatsapp', '+966 XX XXX XXXX');
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
    }
}

if (!function_exists('get_video_embed_url')) {
    /**
     * Convert YouTube/Vimeo URL to embed URL.
     *
     * @param string $url
     * @return string|null
     */
    function get_video_embed_url(string $url): ?string
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        
        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }
        
        return null;
    }
}

if (!function_exists('get_file_icon')) {
    /**
     * Get icon class for file type.
     *
     * @param string $extension
     * @return string
     */
    function get_file_icon(string $extension): string
    {
        $icons = [
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'doc' => 'bi-file-earmark-word text-primary',
            'docx' => 'bi-file-earmark-word text-primary',
            'xls' => 'bi-file-earmark-excel text-success',
            'xlsx' => 'bi-file-earmark-excel text-success',
            'jpg' => 'bi-file-earmark-image text-info',
            'jpeg' => 'bi-file-earmark-image text-info',
            'png' => 'bi-file-earmark-image text-info',
            'zip' => 'bi-file-earmark-zip text-warning',
            'rar' => 'bi-file-earmark-zip text-warning',
        ];
        
        return $icons[strtolower($extension)] ?? 'bi-file-earmark text-secondary';
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size in human-readable format.
     *
     * @param int $bytes
     * @return string
     */
    function format_file_size(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
