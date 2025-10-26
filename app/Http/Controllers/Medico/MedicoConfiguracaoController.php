<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Medico;
use App\Models\Usuario; // 🔥 IMPORTANTE: Adicionar o model Usuario

class MedicoConfiguracaoController extends Controller
{
    public function perfil()
    {
        $usuario = Auth::user();
        
        // Buscar o médico relacionado ao usuário COM o usuário carregado
        $medico = Medico::with('usuario')
            ->where('id_usuarioFK', $usuario->idUsuarioPK)
            ->first();
        
        if (!$medico) {
            return redirect()->route('medico.login')->with('error', 'Médico não encontrado.');
        }

        return view('medico.perfilMedico', compact('medico'));
    }

    public function atualizarPerfil(Request $request)
    {
        $usuario = Auth::user();
        
        // Buscar o médico relacionado ao usuário COM o usuário carregado
        $medico = Medico::with('usuario')
            ->where('id_usuarioFK', $usuario->idUsuarioPK)
            ->first();

        if (!$medico || !$medico->usuario) {
            return redirect()->route('medico.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $request->validate([
            'nomeMedico' => 'required|string|max:255',
            'emailUsuario' => [ // 🔥 MUDOU: emailUsuario em vez de emailMedico
                'required',
                'email',
                'max:255',
                Rule::unique('tbUsuario', 'emailUsuario')->ignore($usuario->idUsuarioPK, 'idUsuarioPK'),
            ],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 🔥 ATUALIZAÇÃO: Atualizar dados em ambas as tabelas
        $medico->nomeMedico = $request->nomeMedico;
        
        // Atualizar email na tabela Usuario
        $medico->usuario->emailUsuario = $request->emailUsuario;

        // Processar foto se for enviada (foto fica na tabela Medico)
        if ($request->hasFile('foto')) {
            // Deletar foto antiga se existir
            if ($medico->foto && Storage::disk('public')->exists('fotos/' . $medico->foto)) {
                Storage::disk('public')->delete('fotos/' . $medico->foto);
            }

            // Salvar nova foto
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            $medico->foto = basename($fotoPath);
        }

        // 🔥 SALVAR: Salvar ambas as models
        $medico->save();
        $medico->usuario->save();

        return redirect()->route('medico.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}