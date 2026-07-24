<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterViewResponse as Contract;
use Illuminate\Http\Request;

class RegisterViewResponse implements Contract
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
     * @param  \Laravel\Fortify\RegisterResponse  $registerResponse
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($registerResponse)
    {
        // For API/token-based guards, don't redirect
        if ($this->guard === 'api' || $this->request->expectsJson()) {
            return response()->json(['message' => 'Registration successful'], 200);
        }

        // Default redirect after registration
        return redirect()->intended('/dashboard');
    }
}
