<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Mostra o formulário de login do admin.
     */
    public function showLoginForm()
    {
        // Supondo que seu arquivo blade se chama 'admin.login'
        // e está em resources/views/admin/login.blade.php
        return view('admin.login'); 
    }

    /**
     * Lida com a tentativa de login do admin.
     */
    public function login(Request $request)
    {
        // 1. Valida os dados do formulário
        $credentials = $request->validate([
            'emailAdmin' => 'required|email',
            'senhaAdmin' => 'required',
        ]);

        // 2. Tenta autenticar o admin
        // 'attempt' já faz a verificação do email e o hash da senha
        if (Auth::guard('admin')->attempt(['emailAdmin' => $credentials['emailAdmin'], 'password' => $credentials['senhaAdmin']])) {
            $request->session()->regenerate();

            // 3. Se der certo, redireciona para o dashboard do admin
            return redirect()->intended('/admin/dashboard');
        }

        // 4. Se falhar, volta para o login com uma mensagem de erro
        return back()->withErrors([
            'emailAdmin' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('emailAdmin');
    }

    /**
     * Faz o logout do admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
