<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect based on guard type
                if ($guard === 'admin' || $request->is('admin/*')) {
                    return redirect('/admin');
                }
                
                // For web guards or other guards, redirect to localized home
                $locale = app()->getLocale() ?: config('app.locale', 'bn');
                return redirect("/{$locale}");
            }
        }

        return $next($request);
    }
}
