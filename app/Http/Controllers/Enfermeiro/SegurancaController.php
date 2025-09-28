<?php

namespace App\Http\Controllers\Enfermeiro; // Corrigido o namespace

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SegurancaController extends Controller
{
    /**
     * Exibe o formulário de alteração de senha.
     */
    public function showAlterarSenhaForm()
    {
        // A view será específica para o enfermeiro
        return view('enfermeiro.seguranca');
    }

    /**
     * Altera a senha do enfermeiro logado.
     */
    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
        ], [
            'nova_senha.confirmed' => 'A confirmação da senha não corresponde.',
            'nova_senha.min' => 'A nova senha precisa ter no mínimo 8 caracteres.',
        ]);

        // Pega o usuário autenticado pelo guard 'enfermeiro'
        // Este usuário deve ser o modelo 'Usuario' que contém o campo senhaUsuario
        $usuario = Auth::guard('enfermeiro')->user(); 

        if (!$usuario) {
            return back()->withErrors(['auth' => 'Não foi possível identificar o enfermeiro logado.']);
        }
        
        // Verifica se a senha atual confere com a senha no banco de dados (senhaUsuario)
        // O campo da senha no seu modelo Usuario (que está sendo autenticado) parece ser 'senhaUsuario'
        if (!Hash::check($request->senha_atual, $usuario->senhaUsuario)) { 
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        // Atualiza a senha
        $usuario->senhaUsuario = Hash::make($request->nova_senha);
        $usuario->save();
        
        return back()->with('success', 'Senha alterada com sucesso!');
    }
}
