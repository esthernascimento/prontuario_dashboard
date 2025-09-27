<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Usuario;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::all();
        return view('admin.manutencaoMedicos', compact('medicos'));
    }

    // mostra o formulário de cadastro
    public function create()
    {
        return view('admin.cadastroMedico');
    }

    // processa o formulário e salva no banco
   public function store(Request $request)
{
    $validated = $request->validate([
        'nomeMedico' => 'required|string|max:255',
        'crmMedico' => 'required|string|max:20',
        'emailUsuario' => 'required|email|max:255|unique:tbUsuario,emailUsuario',
        'senhaUsuario' => 'required|string|min:6',
    ]);

    $usuario = new Usuario();
    $usuario->nomeUsuario = $request->nomeMedico; // ou $request->nomeUsuario se tiver
    $usuario->emailUsuario = $request->emailUsuario;
    $usuario->senhaUsuario = bcrypt($request->senhaUsuario);
    $usuario->statusAtivoUsuario  = 1;

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
    $medico->emailMedico = $request->emailUsuario;
    $medico->save();

    return redirect()->route('admin.manutencaoMedicos')->with('success', 'Médico cadastrado com sucesso!');
}

}

?>