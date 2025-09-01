<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Corrigido o caminho da view
        return view('admin.loginAdm'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'emailAdmin' => 'required|email',
            'senhaAdmin' => 'required',
        ]);

        if (Auth::guard('admin')->attempt([
            'emailAdmin' => $credentials['emailAdmin'],
            'password' => $credentials['senhaAdmin'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'emailAdmin' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('emailAdmin');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/loginAdm'); 
    }
}
