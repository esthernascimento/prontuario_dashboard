<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enfermeiro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('enfermeiro.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'corem' => 'required|string',
            'senha' => 'required|string',
        ]);

        $enfermeiro = Enfermeiro::with('usuario')
            ->where('corenEnfermeiro', $request->corem)
            ->first();

        if ($enfermeiro && $enfermeiro->usuario && Hash::check($request->senha, $enfermeiro->usuario->senhaUsuario)) {
            Auth::guard('enfermeiro')->login($enfermeiro->usuario);
            session([
                'enfermeiro_id' => $enfermeiro->id,
                'enfermeiro_nome' => $enfermeiro->nomeEnfermeiro
            ]);
            return redirect()->route('enfermeiro.dashboard');
        }

        return back()->withErrors([
            'corem' => 'COREM ou senha inválidos.'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('enfermeiro')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('enfermeiro.login');
    }
}
