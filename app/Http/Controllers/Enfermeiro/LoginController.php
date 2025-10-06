<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enfermeiro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('enfermeiro.login');
    }

    public function login(Request $request)
    {
        // Validação básica
        $request->validate([
            'corem' => 'required|string',
            'senha' => 'required|string',
        ]);

        $corem = $request->corem;

        try {
            // Buscamos o enfermeiro pelo COREM
            $enfermeiro = Enfermeiro::with('usuario')
                ->where('corenEnfermeiro', $corem)
                ->first();

            if (!$enfermeiro) {
                return back()->withErrors(['corem' => 'COREM não encontrado.'])->withInput();
            }

            if (!$enfermeiro->usuario) {
                return back()->withErrors(['corem' => 'Usuário não vinculado ao enfermeiro.'])->withInput();
            }

            // Verifica se usuário está ativo
            if (isset($enfermeiro->usuario->statusAtivoUsuario) && !$enfermeiro->usuario->statusAtivoUsuario) {
                return back()->withErrors(['corem' => 'Usuário inativo. Contate o administrador.'])->withInput();
            }

            // Verifica a senha
            if (!Hash::check($request->senha, $enfermeiro->usuario->senhaUsuario)) {
                return back()->withErrors(['corem' => 'Senha inválida.'])->withInput();
            }

            // Autentica usando o guard 'enfermeiro'
            Auth::guard('enfermeiro')->login($enfermeiro->usuario);

            // Grava alguns dados úteis na sessão
            Session::put('enfermeiro_id', $enfermeiro->idEnfermeiroPK ?? $enfermeiro->id);
            Session::put('enfermeiro_nome', $enfermeiro->nomeEnfermeiro);
            Session::put('enfermeiro_coren', $enfermeiro->corenEnfermeiro);
            Session::save();

            return redirect()->route('enfermeiro.dashboard')->with('success', 'Login realizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro no login enfermeiro: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['corem' => 'Erro interno. Tente novamente.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('enfermeiro')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout realizado com sucesso.');
    }
}
    