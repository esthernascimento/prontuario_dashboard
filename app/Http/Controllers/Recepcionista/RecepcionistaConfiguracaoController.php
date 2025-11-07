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
    public function perfil()
    {
        $recepcionista = Auth::guard('recepcionista')->user();

        if (!$recepcionista) {
            return redirect()->route('recepcionista.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        return view('recepcionista.perfilRecepcionista', compact('recepcionista'));
    }

    public function atualizarPerfil(Request $request)
    {
        $recepcionista = Auth::guard('recepcionista')->user();

        if (!$recepcionista) {
            return redirect()->route('recepcionista.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $request->validate([
            'nomeRecepcionista' => 'required|string|max:255',
            'emailRecepcionista' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tbRecepcionista', 'emailRecepcionista')
                    ->ignore($recepcionista->idRecepcionistaPK, 'idRecepcionistaPK'),
            ],
        ], [
            'emailRecepcionista.unique' => 'Este e-mail já está cadastrado em outra conta.',
        ]);

        $recepcionista->nomeRecepcionista = $request->nomeRecepcionista;
        $recepcionista->emailRecepcionista = $request->emailRecepcionista;
        $recepcionista->save();

        return redirect()->route('recepcionista.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    // MÉTODO PARA TROCAR SENHA VIA MODAL
    public function trocarSenha(Request $request)
    {
        $recepcionista = Auth::guard('recepcionista')->user();

        if (!$recepcionista) {
            return redirect()->route('recepcionista.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A nova senha deve ter pelo menos 6 caracteres.',
            'new_password.confirmed' => 'A confirmação da nova senha não coincide.',
        ]);

        // Verificar se a senha atual está correta
        if (!Hash::check($request->current_password, $recepcionista->senhaRecepcionista)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.'])->withInput();
        }

        // Atualizar a senha
        $recepcionista->senhaRecepcionista = Hash::make($request->new_password);
        $recepcionista->save();

        return redirect()->route('recepcionista.perfil')->with('success', 'Senha alterada com sucesso!');
    }
}