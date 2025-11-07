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
    // ... seus outros métodos existentes ...

    /**
     * Mostra o formulário de segurança
     */
    public function showAlterarSenhaForm()
    {
        return view('recepcionista.segurancaRecepcionista');
    }

    /**
     * Altera a senha do recepcionista
     */
    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
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