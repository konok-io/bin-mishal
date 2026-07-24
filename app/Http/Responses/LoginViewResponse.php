<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginViewResponse as Contract;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginViewResponse implements Contract
{
    /**
     * Create a new response instance.
     */
    public function __construct(
        protected Request $request,
        protected ?string $guard = null
    ) {}

    /**
     * Build the response.
     *
     * @param  \Laravel\Fortify\LoginResponse  $loginResponse
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($loginResponse)
    {
        // For API/token-based guards, don't redirect to Inertia
        if ($this->guard === 'api' || $this->request->expectsJson()) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        // Redirect to Inertia dashboard for web guards
        if ($this->guard === 'web') {
            return redirect()->intended('/admin/dashboard');
        }

        // For admin guard, redirect to Filament
        if ($this->guard === 'admin') {
            return redirect()->intended('/admin');
        }

        // Default redirect
        return redirect()->intended('/dashboard');
    }
}
