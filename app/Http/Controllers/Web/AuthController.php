<?php

namespace App\Http\Controllers\Web;

use App\Enums\Currency;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->only(['email', 'password']), $request->boolean('remember'))) {
            return back()->withErrors(['email' => __('auth.invalid_credentials')])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('payment-requests.index'));
    }

    public function showRegisterForm(): View
    {
        $currencies = Currency::cases();

        return view('auth.register', compact('currencies'));
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validated());

        Auth::login($user);

        return redirect(route('payment-requests.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
