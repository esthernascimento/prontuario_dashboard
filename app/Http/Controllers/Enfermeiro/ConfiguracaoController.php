<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enfermeiro; // Importe o seu Model
use Illuminate\Support\Facades\Session; // Importe a Facade Session
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConfiguracaoController extends Controller
{
    /**
     * Exibe a página de perfil do enfermeiro.
     */
    public function perfil()
    {
        // Pega o ID do enfermeiro da sessão
        $enfermeiroId = Session::get('enfermeiro_id');

        // Se não houver ID na sessão, redireciona para o login
        if (!$enfermeiroId) {
            return redirect()->route('enfermeiro.login')->with('error', 'Sessão inválida. Faça login novamente.');
        }

        // Busca o enfermeiro no banco de dados com o ID da sessão
        $enfermeiro = Enfermeiro::find($enfermeiroId);

        // Envia os dados do enfermeiro para a view
        return view('enfermeiro.perfilEnfermeiro', compact('enfermeiro'));
    }

    /**
     * Atualiza os dados do perfil do enfermeiro.
     */
    public function atualizarPerfil(Request $request)
    {
        // Pega o ID do enfermeiro da sessão para encontrar o usuário correto
        $enfermeiroId = Session::get('enfermeiro_id');

        if (!$enfermeiroId) {
            return redirect()->route('enfermeiro.login')->with('error', 'Sessão expirada. Faça login novamente.');
        }

        // Busca o enfermeiro que será atualizado
        $enfermeiro = Enfermeiro::find($enfermeiroId);

        // Se o ID da sessão for inválido e não encontrar um enfermeiro
        if (!$enfermeiro) {
            return redirect()->route('enfermeiro.login')->with('error', 'Usuário não encontrado. Faça login novamente.');
        }

        // Validação dos dados (seu código de validação já estava perfeito)
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

        // Atualiza os dados do enfermeiro com as informações do formulário
        $enfermeiro->nomeEnfermeiro = $request->nomeEnfermeiro;
        $enfermeiro->emailEnfermeiro = $request->emailEnfermeiro;

        // Lógica para upload da foto (seu código já estava perfeito)
        if ($request->hasFile('foto')) {
            // Deleta a foto antiga, se existir
            if ($enfermeiro->foto && Storage::disk('public')->exists('fotos/' . $enfermeiro->foto)) {
                Storage::disk('public')->delete('fotos/' . $enfermeiro->foto);
            }
            // Salva a nova foto
            $fotoPath = $request->file('foto')->store('fotos', 'public');
            $enfermeiro->foto = basename($fotoPath);
        }

        // Salva as alterações no banco de dados
        $enfermeiro->save();

        // Redireciona de volta para a página de perfil com uma mensagem de sucesso
        return redirect()->route('enfermeiro.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }
}