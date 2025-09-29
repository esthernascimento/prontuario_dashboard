<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MedicoConfiguracaoController extends Controller
{
    public function perfil()
    {
        $medico = Auth::user();
        return view('medico.perfilMedico', compact('medico'));
    }

    public function atualizarPerfil(Request $request)
    {
        $medico = Auth::user();

        if (!$medico) {
            return redirect()->route('medico.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $request->validate([
            'nomeMedico' => 'required|string|max:255',
            'emailMedico' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tbMedico', 'emailMedico')->ignore($medico->idMedicoPK, 'idMedicoPK'),
            ],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $medico->nomeMedico = $request->nomeMedico;
        $medico->emailMedico = $request->emailMedico;

        if ($request->hasFile('foto')) {
            if ($medico->foto && Storage::disk('public')->exists('fotos/' . $medico->foto)) {
                Storage::disk('public')->delete('fotos/' . $medico->foto);
            }

            $fotoPath = $request->file('foto')->store('fotos', 'public');
            $medico->foto = basename($fotoPath);
        }

        $medico->save();

        return redirect()->route('medico.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}
