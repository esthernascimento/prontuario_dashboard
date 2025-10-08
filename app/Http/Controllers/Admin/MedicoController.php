<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Unidade; // Adicionado para buscar as unidades
use Illuminate\Validation\ValidationException;
use App\Mail\emailMedico;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::with('usuario')->get();
        return view('admin.manutencaoMedicos', compact('medicos'));
    }

    /**
     * AJUSTADO: Mostra o formulário de cadastro E envia a lista de unidades.
     */
    public function create()
    {
        // Busca todas as unidades para listarmos no formulário de seleção
        $unidades = Unidade::orderBy('nomeUnidade')->get();
        return view('admin.cadastroMedico', compact('unidades'));
    }

    /**
     * AJUSTADO: Salva o novo médico E as suas unidades de trabalho.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomeMedico'      => 'required|string|max:255',
                'crmMedico'       => 'required|string|max:20|unique:tbMedico,crmMedico',
                'emailUsuario'    => 'required|email|max:255|unique:tbUsuario,emailUsuario',
                'especialidadeMedico' => 'nullable|string|max:100',
                'unidades'        => 'nullable|array', // Valida que 'unidades' é uma lista (se enviada)
                'unidades.*'      => 'exists:tbUnidade,idUnidadePK', // Valida cada ID da lista
            ], [
                'nomeMedico.required' => 'O nome do médico é obrigatório.',
                'crmMedico.required' => 'O CRM é obrigatório.',
                'emailUsuario.required' => 'O e-mail é obrigatório.',
                'emailUsuario.unique' => 'Este e-mail já está cadastrado.',
            ]);

            $senhaTemporaria = Str::random(10);

            $usuario = new Usuario();
            $usuario->nomeUsuario = $request->nomeMedico;
            $usuario->emailUsuario = $request->emailUsuario;
            $usuario->senhaUsuario = Hash::make($senhaTemporaria);
            $usuario->statusAtivoUsuario = 1;
            $usuario->statusSenhaUsuario = true; 
            $usuario->save();

            $medico = new Medico();
            $medico->id_usuarioFK = $usuario->idUsuarioPK;
            $medico->nomeMedico = $request->nomeMedico;
            $medico->crmMedico = $request->crmMedico;
            $medico->especialidadeMedico = $request->input('especialidadeMedico', ''); // Valor padrão vazio se não for fornecido
            $medico->save();

            // Se o admin selecionou unidades no formulário, associa-as ao médico
            if ($request->has('unidades')) {
                $medico->unidades()->sync($request->unidades);
            }

            Mail::to($usuario->emailUsuario)->send(new emailMedico($usuario, $senhaTemporaria));

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
                'message' => 'Erro interno ao cadastrar médico: ' . $e->getMessage() // Adicionado para ajudar a depurar
            ], 500);
        }
    }
    
    // REMOVEMOS AS FUNÇÕES 'confirmarExclusao' e 'excluir'
    // A ação de "excluir" agora será uma desativação através do toggleStatus.
    // O código abaixo pode ser removido do seu controller.
    /*
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
    */

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
        $mensagem = '';

        if (!$medico->usuario) {
            return redirect()->route('admin.manutencaoMedicos')->with('error', 'Este médico não está vinculado a um usuário.');
        }

        $usuario = $medico->usuario;
        $novoStatus = !$usuario->statusAtivoUsuario;
        $usuario->statusAtivoUsuario = $novoStatus;
        $usuario->save();

        $acao = $novoStatus ? 'ativado' : 'desativado';
        $mensagem = "O médico foi {$acao} com sucesso!";

        return redirect()->route('admin.manutencaoMedicos')->with('success', $mensagem);
    }
}