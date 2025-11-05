<?php

namespace App\Http\Controllers\Unidade;

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
        // Valida o CNPJ e a senha
        $credentials = $request->validate([
            'cnpjUnidade' => 'required|string',
            'senhaUnidade' => 'required|string',
        ]);

        // Tenta autenticar usando o CNPJ
        if (Auth::guard('unidade')->attempt([
            'cnpjUnidade' => $credentials['cnpjUnidade'],
            'password' => $credentials['senhaUnidade'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/unidade/dashboard');
        }

        return back()->withErrors([
            'cnpjUnidade' => 'CNPJ ou senha inválidos.',
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
