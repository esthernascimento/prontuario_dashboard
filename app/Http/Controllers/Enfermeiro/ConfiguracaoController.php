<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Enfermeiro;
use App\Models\Usuario; // 🔥 IMPORTANTE: Adicionar o model Usuario

class ConfiguracaoController extends Controller
{
    public function perfil()
    {
        $usuario = Auth::guard('enfermeiro')->user();
        
        // Buscar o enfermeiro relacionado ao usuário COM o usuário carregado
        $enfermeiro = Enfermeiro::with('usuario')
            ->where('id_usuario', $usuario->idUsuarioPK)
            ->first();
        
        if (!$enfermeiro) {
            return redirect()->route('enfermeiro.login')->with('error', 'Enfermeiro não encontrado.');
        }

        return view('enfermeiro.perfilEnfermeiro', compact('enfermeiro'));
    }

    public function atualizarPerfil(Request $request)
    {
        $usuario = Auth::guard('enfermeiro')->user();
        
        // Buscar o enfermeiro relacionado ao usuário COM o usuário carregado
        $enfermeiro = Enfermeiro::with('usuario')
            ->where('id_usuario', $usuario->idUsuarioPK)
            ->first();

        if (!$enfermeiro || !$enfermeiro->usuario) {
            return redirect()->route('enfermeiro.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $request->validate([
            'nomeEnfermeiro' => 'required|string|max:255',
            'emailEnfermeiro' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tbEnfermeiro', 'emailEnfermeiro')->ignore($enfermeiro->idEnfermeiroPK, 'idEnfermeiroPK'),
            ],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'emailEnfermeiro.required' => 'O campo E-mail é obrigatório.',
            'emailEnfermeiro.email' => 'O E-mail inserido não é válido.',
            'emailEnfermeiro.unique' => 'Este e-mail já está cadastrado em outra conta de enfermeiro.',
        ]);

        // Atualiza os dados do enfermeiro
        $enfermeiro->nomeEnfermeiro = $request->nomeEnfermeiro;
        $enfermeiro->emailEnfermeiro = $request->emailEnfermeiro;

        // 🔥 CORREÇÃO: Foto salva na tabela Usuario
        if ($request->hasFile('foto')) {
            // Deleta a foto antiga, se existir (da tabela Usuario)
            if ($enfermeiro->usuario->foto && Storage::disk('public')->exists('fotos/' . $enfermeiro->usuario->foto)) {
                Storage::disk('public')->delete('fotos/' . $enfermeiro->usuario->foto);
            }

            // Salva a nova foto na tabela Usuario
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            $enfermeiro->usuario->foto = basename($fotoPath);
        }

        // 🔥 SALVA: Ambos os modelos
        $enfermeiro->save();
        $enfermeiro->usuario->save();

        return redirect()->route('enfermeiro.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}