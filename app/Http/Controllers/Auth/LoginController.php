<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            $planMessage = $user->planStatusMessage();

            if ($planMessage !== null) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['email' => $planMessage])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();
            if ($user->user_type === \App\Models\User::TYPE_BIDDER || $user->hasRole(\App\Models\User::TYPE_BIDDER)) {
                return redirect()->intended(route('public.auctions.index'));
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
