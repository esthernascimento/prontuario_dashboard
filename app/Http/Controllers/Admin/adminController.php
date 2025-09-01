<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;

class AdminController extends Controller
{
    // Exibe a tela de confirmação de exclusão
    public function confirmarExclusao($id)
    {
        $medico = Medico::findOrFail($id);
        return view('excluirMedico', compact('medico'));
    }

    // Executa a exclusão do médico
    public function excluir($id)
    {
        $medico = Medico::findOrFail($id);
        $medico->delete();

        return redirect()->route('admin.manutencaoMedicos')->with('success', 'Médico excluído com sucesso!');
    }

    // Exibe o formulário de edição
    public function editar($id)
    {
        $medico = Medico::findOrFail($id);
        return view('editarMedico', compact('medico'));
    }

    // Atualiza os dados do médico
 public function update(Request $request, $id)
{
    $request->validate([
        'nomeMedico' => 'required|string|max:255',
        'crmMedico' => 'required|string|max:20',
        'especialidadeMedico' => 'nullable|string|max:255',
        'nomeUsuario' => 'required|string|max:255',
        'emailUsuario' => 'required|email|max:255',
        'senhaUsuario' => 'nullable|string|min:6',
        'foto' => 'nullable|image|max:2048',
    ]);

    $medico = Medico::with('usuario')->findOrFail($id);

    // Atualiza dados do médico
    $medico->update([
        'nomeMedico' => $request->nomeMedico,
        'crmMedico' => $request->crmMedico,
        'especialidadeMedico' => $request->especialidadeMedico,
    ]);

    // Atualiza dados do usuário vinculado
    $usuario = $medico->usuario;
    $usuario->nomeUsuario = $request->nomeUsuario;
    $usuario->emailUsuario = $request->emailUsuario;

    if ($request->filled('senhaUsuario')) {
        $usuario->senhaUsuario = bcrypt($request->senhaUsuario);
    }

    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('public/fotos');
        $usuario->foto = basename($fotoPath);
    }

    $usuario->save();

    return redirect()->route('admin.manutencaoMedicos')->with('success', 'Dados atualizados com sucesso!');
}
}
