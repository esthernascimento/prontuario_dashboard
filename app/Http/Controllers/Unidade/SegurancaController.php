<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SegurancaController extends Controller 
{
   
    public function showAlterarSenhaForm()
    {
        return view('unidade.seguranca');
    }

    public function atualizarPerfil(Request $request)
    {
        $unidade = Auth::guard('unidade')->user();

        $request->validate([
            'nomeUnidade' => 'required|string|max:255',
            'emailUnidade' => 'required|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $unidade->update([
            'nomeUnidade' => $request->nomeUnidade,
            'emailUnidade' => $request->emailUnidade,
        ]);

        // Se a unidade tem campo de foto próprio, use isso:
        if ($request->hasFile('foto')) {
            if ($unidade->foto && Storage::disk('public')->exists('fotos/' . $unidade->foto)) {
                Storage::disk('public')->delete('fotos/' . $unidade->foto);
            }

            // Salva nova foto
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            $unidade->foto = basename($fotoPath);
            $unidade->save();
        }

        return redirect()->route('unidade.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:8|confirmed',
        ], [
            'nova_senha.confirmed' => 'A confirmação da senha não corresponde.',
            'nova_senha.min' => 'A nova senha precisa ter no mínimo 8 caracteres.',
        ]);

        $unidade = Auth::guard('unidade')->user();

        if (!Hash::check($request->senha_atual, $unidade->senhaUnidade)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        $unidade->senhaUnidade = Hash::make($request->nova_senha);
        $unidade->save();
        
        return back()->with('success', 'Senha alterada com sucesso!');
    }
} 