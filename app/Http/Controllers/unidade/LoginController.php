<?php

namespace App\Http\Controllers\unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('unidade.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'emailUnidade' => 'required|email',
            'senhaUnidade' => 'required',
        ]);

        if (Auth::guard('unidade')->attempt([
            'emailUnidade' => $credentials['emailUnidade'],
            'password' => $credentials['senhaUnidade'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/unidade/dashboard');
        }

        return back()->withErrors([
            'emailUnidade' => 'Credenciais inválidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('unidade')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
