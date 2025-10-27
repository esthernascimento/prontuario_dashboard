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
            return redirect()->route('loginRecepcionista')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        return view('recepcionista.perfilRecepcionista', compact('recepcionista'));
    }

    public function atualizarPerfil(Request $request)
    {
        $recepcionista = Auth::guard('recepcionista')->user();

        if (!$recepcionista) {
            return redirect()->route('loginRecepcionista')->with('error', 'Sessão expirada. Faça login novamente.');
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
            'senhaRecepcionista' => 'nullable|string|min:6|confirmed',
        ], [
            'emailRecepcionista.unique' => 'Este e-mail já está cadastrado em outra conta.',
            'senhaRecepcionista.confirmed' => 'As senhas não coincidem.',
        ]);


        $recepcionista->nomeRecepcionista = $request->nomeRecepcionista;
        $recepcionista->emailRecepcionista = $request->emailRecepcionista;

        if (!empty($request->senhaRecepcionista)) {
            $recepcionista->senhaRecepcionista = Hash::make($request->senhaRecepcionista);
        }

        $recepcionista->save();

        return redirect()->route('recepcionista.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}
