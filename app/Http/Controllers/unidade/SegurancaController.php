<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SegurancaController extends Controller 
{
    /**
     * Exibe o formulário de alteração de senha.
     */
    public function showAlterarSenhaForm()
    {
        return view('unidade.seguranca');
    }

    /**
     * 🔥 MOVIDO DO UnidadeController: Processa a atualização do perfil da unidade e do usuário relacionado.
     */
    public function atualizarPerfil(Request $request)
    {
        $unidade = Auth::guard('unidade')->user();

        $request->validate([
            'nomeUnidade' => 'required|string|max:255',
            'emailUnidade' => 'required|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Atualiza os dados da tabela Unidade
        $unidade->update([
            'nomeUnidade' => $request->nomeUnidade,
            'emailUnidade' => $request->emailUnidade,
        ]);

        // Atualiza a foto na tabela Usuario (se houver)
        if ($request->hasFile('foto')) {
            $usuario = $unidade->usuario; // Assumindo que existe relacionamento usuario()
            if ($usuario) {
                // Deletar foto antiga
                if ($usuario->foto && Storage::disk('public')->exists('fotos/' . $usuario->foto)) {
                    Storage::disk('public')->delete('fotos/' . $usuario->foto);
                }

                // Salvar nova foto
                $fotoPath = $request->file('foto')->store('fotos', 'public');
                $usuario->foto = basename($fotoPath);
                $usuario->save();
            }
        }

        return redirect()->route('unidade.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * 🔥 MOVIDO DO UnidadeController: Processa a alteração de senha do usuário logado.
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

        $unidade = Auth::guard('unidade')->user();
        $usuario = $unidade->usuario; // Pega o usuário relacionado

        if (!$usuario) {
            return back()->withErrors(['auth' => 'Não foi possível identificar o usuário logado.']);
        }

        if (!Hash::check($request->senha_atual, $usuario->senhaUsuario)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        $usuario->senhaUsuario = Hash::make($request->nova_senha);
        $usuario->save();
        
        return back()->with('success', 'Senha alterada com sucesso!');
    }
}