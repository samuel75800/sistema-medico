<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private const ADMIN_EMAIL = 'admin@hospital.com';

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        // Bloqueia qualquer usuário que não seja o admin
        if (trim($request->email) !== self::ADMIN_EMAIL) {
            return back()->withErrors(['email' => 'Acesso não autorizado.'])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha incorretos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}