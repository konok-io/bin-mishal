<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->success([
            'user' => $user->load('roles'),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'user_type' => 'customer',
            'status' => 'active',
        ]);

        $user->assignRole('customer');

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->success([
            'user' => $user->load('roles'),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registration successful', 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->success($request->user()->load(['roles', 'customer', 'employee']));
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'preferred_language' => 'nullable|in:en,bn,ar',
        ]);

        $user->update($request->only([
            'name', 'name_bn', 'name_ar', 'phone', 'whatsapp', 'preferred_language'
        ]));

        return $this->success($user, 'Profile updated successfully');
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Current password is incorrect', 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return $this->success(null, 'Password changed successfully');
    }
}
