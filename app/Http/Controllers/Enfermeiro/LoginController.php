<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enfermeiro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        $corem = $request->corem;

        try {
            
            $enfermeiro = Enfermeiro::with('usuario')
                ->where('corenEnfermeiro', $corem)
                ->first();

            if (!$enfermeiro || !$enfermeiro->usuario) {
                return response()->json(['message' => 'COREM inválido.'], 401);
            }
            if (!Hash::check($request->senha, $enfermeiro->usuario->senhaUsuario)) {
                return response()->json(['message' => 'Senha incorreta.'], 401);
            }
            if (isset($enfermeiro->usuario->statusAtivoUsuario) && !$enfermeiro->usuario->statusAtivoUsuario) {
                 return response()->json(['message' => 'Usuário inativo.'], 403);
            }
            Auth::guard('enfermeiro')->login($enfermeiro->usuario);
            $request->session()->regenerate();

            if ($enfermeiro->usuario->statusSenhaUsuario == 1) {
                return response()->json([
                    'need_password_change' => true,
                    'message' => 'Você precisa alterar sua senha antes de continuar.',
                    'new_csrf_token' => csrf_token(),
                ]);
            }

            return response()->json([
                'profile_complete' => true, 
                'redirect_url' => route('enfermeiro.dashboard'),
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no login enfermeiro: '.$e->getMessage());
            return response()->json(['message' => 'Erro interno do servidor.'], 500);
        }
    }

    public function alterarSenhaPrimeiroLogin(Request $request)
    {
        $request->validate([
            'corem' => 'required|string',
            'nova_senha' => 'required|string|min:8|confirmed',
        ]);

        $enfermeiro = Enfermeiro::with('usuario')->where('corenEnfermeiro', $request->corem)->first();

        if (!$enfermeiro || !$enfermeiro->usuario) {
            return response()->json(['message' => 'Enfermeiro não encontrado.'], 404);
        }

        $usuario = $enfermeiro->usuario;

        if ($usuario->statusSenhaUsuario != 1) {
             return response()->json(['message' => 'A senha já foi alterada.'], 403);
        }

        $usuario->senhaUsuario = Hash::make($request->nova_senha);
        $usuario->statusSenhaUsuario = 0;
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Senha alterada com sucesso!',
            'redirect_url' => route('enfermeiro.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('enfermeiro')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout realizado com sucesso.'); 
    }
}
