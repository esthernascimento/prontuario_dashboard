<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Medico;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('medico.loginMedico');
    }

    public function login(Request $request)
    {
        $request->validate([
            'crm' => 'required|string',
            'senha' => 'required|string',
        ]);

        $medico = Medico::with('usuario')->where('crmMedico', $request->crm)->first();

        if (!$medico || !$medico->usuario) {
            return response()->json(['message' => 'CRM inválido.'], 401);
        }

        if (!Hash::check($request->senha, $medico->usuario->senhaUsuario)) {
            return response()->json(['message' => 'Senha incorreta.'], 401);
        }

        Auth::guard('medico')->login($medico->usuario);

        // ✅ Adicionar a regeneração da sessão aqui
        $request->session()->regenerate();
        // O token CSRF da sessão agora será atualizado,
        // mas o token no frontend (que está no <meta>) permanece o mesmo.

        // ✅ Verifica se é o primeiro login (statusSenhaUsuario == 1)
        if ($medico->usuario->statusSenhaUsuario == 1) {
            return response()->json([
                'need_password_change' => true,
                'message' => 'Você precisa alterar sua senha antes de continuar.',
                // Podemos incluir o novo token para ter certeza (opcional, mas recomendado)
                'new_csrf_token' => csrf_token(),
            ]);
        }

        // Login normal
        return response()->json([
            'profile_complete' => true,
            'redirect_url' => route('medico.dashboard'),
        ]);
    }

    public function alterarSenhaPrimeiroLogin(Request $request)
    {
        $request->validate([
            'crm' => 'required|string',
            'nova_senha' => 'required|string|min:8|confirmed',
        ]);

        $medico = Medico::with('usuario')->where('crmMedico', $request->crm)->first();

        if (!$medico || !$medico->usuario) {
            return response()->json(['message' => 'Médico não encontrado.'], 404);
        }

        $usuario = $medico->usuario;
        $usuario->senhaUsuario = Hash::make($request->nova_senha);
        $usuario->statusSenhaUsuario = 0; // ✅ agora ele não precisa mais trocar
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Senha alterada com sucesso!',
            'redirect_url' => route('medico.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('medico')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}