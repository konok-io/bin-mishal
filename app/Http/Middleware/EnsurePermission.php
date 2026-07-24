<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            // Redirect to admin login if accessing admin routes, otherwise to root
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            return redirect()->to('/');
        }

        foreach ($permissions as $permission) {
            if ($request->user()->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
