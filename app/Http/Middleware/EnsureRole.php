<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = null;
        
        // Determine which guard to use based on route
        if ($request->is('admin/*')) {
            $user = Auth::guard('admin')->user();
        } elseif ($request->is('*/employee/*')) {
            $user = Auth::guard('employee')->user();
        } else {
            $user = $request->user();
        }
        
        if (!$user) {
            // Redirect to appropriate login if accessing protected routes
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            return redirect()->to('/');
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
