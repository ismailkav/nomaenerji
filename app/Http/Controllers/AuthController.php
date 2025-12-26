<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
{
    $data = $request->validate([
        'mail' => ['required', 'email'],
        'sifre' => ['required'],
    ]);

    $user = User::where('mail', $data['mail'])->first();

    if ($user) {
        // 1) Bcrypt/hash ile kontrol
        if (Hash::check($data['sifre'], $user->sifre)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // 2) Eski düz şifreyi kabul et ve ilk girişte hash’le
        if ($user->sifre === $data['sifre']) {
            $user->sifre = $data['sifre']; // User modelindeki 'hashed' cast sayesinde bcrypt olarak kaydolur
            $user->save();

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
    }

    return back()->withErrors([
        'mail' => 'The provided credentials do not match our records.',
    ])->onlyInput('mail');
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
