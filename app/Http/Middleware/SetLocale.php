<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        if (!$this->isValidLocale($locale)) {
            return $this->redirectToDefault($request);
        }

        $this->applyLocale($locale);

        $response = $next($request);

        $this->setLocaleCookie($locale, $response);

        return $response;
    }

    protected function resolveLocale(Request $request): ?string
    {
        $enabledLocales = array_keys(config('locales.enabled', []));

        // 1. From route segment (highest priority)
        $routeLocale = $request->route('locale');
        if ($routeLocale && $this->isValidLocale($routeLocale)) {
            return $routeLocale;
        }

        // 2. From session
        if ($sessionLocale = Session::get('locale')) {
            if ($this->isValidLocale($sessionLocale)) {
                return $sessionLocale;
            }
        }

        // 3. From cookie
        if ($cookieLocale = Cookie::get('locale')) {
            if ($this->isValidLocale($cookieLocale)) {
                return $cookieLocale;
            }
        }

        // 4. From authenticated user preference
        if ($request->user()?->preferred_language) {
            $userLocale = $request->user()->preferred_language;
            if ($this->isValidLocale($userLocale)) {
                return $userLocale;
            }
        }

        // 5. From Accept-Language header
        $acceptLanguage = $request->header('Accept-Language');
        if ($acceptLanguage) {
            foreach ($enabledLocales as $enabled) {
                if (str_starts_with(strtolower($acceptLanguage), $enabled)) {
                    return $enabled;
                }
            }
        }

        // 6. From config default
        return config('locales.default', 'bn');
    }

    protected function isValidLocale(?string $locale): bool
    {
        if (!$locale) {
            return false;
        }

        $enabledLocales = config('locales.enabled', []);
        return isset($enabledLocales[$locale]) && ($enabledLocales[$locale]['enabled'] ?? false);
    }

    protected function applyLocale(string $locale): void
    {
        App::setLocale($locale);

        // Persist locale to session so subsequent requests use it
        Session::put('locale', $locale);

        // Share locale config with all views
        View::share('localeConfig', config("locales.enabled.{$locale}"));
        View::share('currentLocale', $locale);
        View::share('enabledLocales', config('locales.enabled', []));
    }

    protected function setLocaleCookie(string $locale, Response $response): void
    {
        // Queue a 1-year cookie
        Cookie::queue('locale', $locale, 60 * 24 * 365);
    }

    protected function redirectToDefault(Request $request): Response
    {
        $defaultLocale = config('locales.default', 'bn');
        $path = $request->path();
        $query = $request->getQueryString();

        // Replace leading locale code with default
        $newPath = preg_replace('/^(bn|en|ar)\//', '', $path);
        $newPath = $defaultLocale . ($newPath ? '/' . ltrim($newPath, '/') : '');

        if ($query) {
            $newPath .= '?' . $query;
        }

        return Redirect::to('/' . ltrim($newPath, '/'), 302);
    }
}
