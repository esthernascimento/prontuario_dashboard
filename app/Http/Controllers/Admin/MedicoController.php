<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Usuario;
use Illuminate\Validation\ValidationException;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::with('usuario')->get();
        return view('admin.manutencaoMedicos', compact('medicos'));
    }

    public function create()
    {
        return view('admin.cadastroMedico');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomeMedico'      => 'required|string|max:255',
                'crmMedico'       => 'required|string|max:20',
                'emailUsuario'    => 'required|email|max:255|unique:tbUsuario,emailUsuario',
                'senhaUsuario'    => 'required|string|min:6',
            ], [
                'nomeMedico.required' => 'O nome do médico é obrigatório.',
                'crmMedico.required' => 'O CRM é obrigatório.',
                'emailUsuario.required' => 'O e-mail é obrigatório.',
                'emailUsuario.unique' => 'Este e-mail já está cadastrado.',
                'senhaUsuario.required' => 'A senha é obrigatória.',
           
            ]);

            $usuario = new Usuario();
            $usuario->nomeUsuario = $request->nomeMedico;
            $usuario->emailUsuario = $request->emailUsuario;
            $usuario->senhaUsuario = bcrypt($request->senhaUsuario);
            $usuario->statusAtivoUsuario = 1;
            $usuario->save();

            $medico = new Medico();
            $medico->id_usuarioFK = $usuario->idUsuarioPK;
            $medico->nomeMedico = $request->nomeMedico;
            $medico->crmMedico = $request->crmMedico;
            $medico->especialidadeMedico = $request->especialidadeMedico;
            $medico->save();

            return response()->json([
                'success' => true,
                'message' => 'Médico pré-cadastrado com sucesso!'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao cadastrar médico.'
            ], 500);
        }
    }
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

?>

