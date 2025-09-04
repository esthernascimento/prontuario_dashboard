<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Usuario;

class AdminController extends Controller
{
    public function confirmarExclusao($id)
    {
        $medico = Medico::findOrFail($id);
        return view('admin.excluirMedico', compact('medico'));
    }

    public function excluir($id)
    {
        $medico = Medico::findOrFail($id);
        $medico->delete();

        return redirect()->route('admin.manutencaoMedicos')->with('success', 'Médico excluído com sucesso!');
    }

    public function editar($id)
    {
        $medico = Medico::findOrFail($id);
        return view('admin.editarMedico', compact('medico'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomeMedico' => 'required|string|max:255',
            'nomeUsuario' => 'required|string|max:255',
            'emailUsuario' => 'required|email|max:255',
        ]);

        $medico = Medico::with('usuario')->findOrFail($id);

        $medico->update([
            'nomeMedico' => $request->nomeMedico,
        ]);

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

    public function store(Request $request)
    {
        $request->validate([
            'nomeMedico' => 'required|string|max:255',
            'crmMedico' => 'required|string|max:20',
            'especialidadeMedico' => 'nullable|string|max:255',
            'emailMedico' => 'required|email|max:255',
            'nomeUsuario' => 'required|string|max:255',
            'emailUsuario' => 'required|email|max:255',
            'senhaUsuario' => 'required|string|min:6',
        ]);

        $usuario = new Usuario();
        $usuario->nomeUsuario = $request->nomeUsuario;
        $usuario->emailUsuario = $request->emailUsuario;
        $usuario->senhaUsuario = bcrypt($request->senhaUsuario);
        $usuario->statusUsuario = 1;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('public/fotos');
            $usuario->foto = basename($fotoPath);
        }

        $usuario->save();

        $medico = new Medico();
        $medico->id_usuarioFK = $usuario->idUsuarioPK;
        $medico->nomeMedico = $request->nomeMedico;
        $medico->crmMedico = $request->crmMedico;
        $medico->especialidadeMedico = $request->especialidadeMedico;
        $medico->emailMedico = $request->emailMedico;
        $medico->save();

        return redirect()->route('admin.manutencaoMedicos')->with('success', 'Médico cadastrado com sucesso!');
    }

    public function toggleStatus($id)
    {
        $medico = Medico::with('usuario')->findOrFail($id);

        if (!$medico->usuario) {
            return redirect()->route('admin.manutencaoMedicos')->with('error', 'Este médico não está vinculado a um usuário.');
        }

        $usuario = $medico->usuario;
        $usuario->statusAtivoUsuario = $usuario->statusAtivoUsuario == 1 ? 0 : 1;
        $usuario->save();

        return redirect()->route('admin.manutencaoMedicos')->with('success', 'Status do médico atualizado com sucesso!');
    }
}
