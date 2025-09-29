<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MedicoSegurancaController extends Controller
{
    public function showAlterarSenhaForm()
    {
        return view('medico.segurancaMedico');
    }

    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
        ]);

        $usuario = Auth::user();

        if (!$usuario) {
            return back()->withErrors(['auth' => 'Não foi possível identificar o médico logado.']);
        }

        if (!Hash::check($request->senha_atual, $usuario->senhaUsuario)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        $usuario->senhaUsuario = Hash::make($request->nova_senha);
        $usuario->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}
