<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Recepcionista;

class RecepcionistaConfiguracaoController extends Controller
{
    /**
     * Mostra o formulário de perfil
     */
    public function perfil()
    {
        $recepcionista = Auth::guard('recepcionista')->user();
        return view('recepcionista.perfil', compact('recepcionista'));
    }

    /**
     * Atualiza o perfil do recepcionista
     */
    public function atualizarPerfil(Request $request)
    {
        $request->validate([
            'nomeRecepcionista' => 'required|string|max:255',
            'emailRecepcionista' => 'required|email|max:255',
        ]);

        $recepcionista = Auth::guard('recepcionista')->user();
        $recepcionista->nomeRecepcionista = $request->nomeRecepcionista;
        $recepcionista->emailRecepcionista = $request->emailRecepcionista;
        $recepcionista->save();

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Troca a senha do recepcionista (chamado via modal no perfil)
     */
    public function trocarSenha(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
            'new_password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $recepcionista = Auth::guard('recepcionista')->user();

        if (!$recepcionista) {
            return back()->withErrors(['auth' => 'Não foi possível identificar o recepcionista logado.']);
        }

        // Verifica se a senha atual está correta
        if (!Hash::check($request->current_password, $recepcionista->senhaRecepcionista)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        // Atualiza a senha
        $recepcionista->senhaRecepcionista = Hash::make($request->new_password);
        $recepcionista->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Mostra o formulário de segurança
     */
    public function showAlterarSenhaForm()
    {
        return view('recepcionista.segurancaRecepcionista');
    }

    /**
     * Altera a senha do recepcionista (página de segurança dedicada)
     */
    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
        ], [
            'senha_atual.required' => 'A senha atual é obrigatória.',
            'nova_senha.required' => 'A nova senha é obrigatória.',
            'nova_senha.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $recepcionista = Auth::guard('recepcionista')->user();

        if (!$recepcionista) {
            return back()->withErrors(['auth' => 'Não foi possível identificar o recepcionista logado.']);
        }

        // Verifica se a senha atual está correta
        if (!Hash::check($request->senha_atual, $recepcionista->senhaRecepcionista)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        // Atualiza a senha
        $recepcionista->senhaRecepcionista = Hash::make($request->nova_senha);
        $recepcionista->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}