<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.register', ['guard' => $request->guard ?? 'customer']);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'iqama_no' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'iqama_no' => $request->iqama_no,
            'password' => Hash::make($request->password),
            'user_type' => UserType::CUSTOMER,
            'status' => UserStatus::ACTIVE,
            'preferred_language' => app()->getLocale(),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $user->assignRole('customer');

        event(new Registered($user));

        Auth::guard('web')->login($user);

        return redirect()->to('/' . app()->getLocale());
    }
}
