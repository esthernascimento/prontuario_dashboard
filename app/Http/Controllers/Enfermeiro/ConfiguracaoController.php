<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Validation\Rule; 

class ConfiguracaoController extends Controller
{
    public function perfil()
    {
        $enfermeiro = Auth::guard('enfermeiro')->user(); 

        return view('enfermeiro.perfilEnfermeiro', compact('enfermeiro')); 
    }

   
    public function atualizarPerfil(Request $request)
    {

        $enfermeiro = Auth::guard('enfermeiro')->user();

        if (!$enfermeiro) {

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


        $enfermeiro->nomeEnfermeiro = $request->nomeEnfermeiro;

        $enfermeiro->emailEnfermeiro = $request->emailEnfermeiro;
        
        if ($request->hasFile('foto')) {

            if ($enfermeiro->foto && Storage::disk('public')->exists('fotos/' . $enfermeiro->foto)) {
                Storage::disk('public')->delete('fotos/' . $enfermeiro->foto);
            }
            
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            
            $enfermeiro->foto = basename($fotoPath);
        }

        $enfermeiro->save();

        return redirect()->route('enfermeiro.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}
