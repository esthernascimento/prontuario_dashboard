<?php

namespace App\Http\Controllers\Unidade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Unidade; 
use Illuminate\Validation\ValidationException;
use App\Mail\emailMedico;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class MedicoController extends Controller
{
   public function index(Request $request)
{
    // Pega a unidade logada
    $unidade = auth()->guard('unidade')->user();
    
    // --- 1. Prepara a Query Base com Filtros ---
    $query = $unidade->medicos()->with('usuario');

    // Filtro de busca por nome, CRM ou email
    if ($request->filled('search')) {
        $searchTerm = $request->get('search');
        $query->where('nomeMedico', 'LIKE', "%{$searchTerm}%")
              ->orWhere('crmMedico', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('usuario', function($q) use ($searchTerm) {
                  $q->where('emailUsuario', 'LIKE', "%{$searchTerm}%");
              });
    }

    // Filtro de status (ativo/inativo)
    if ($request->filled('status')) {
        $status = $request->get('status');
        if ($status === 'ativo') {
            $query->whereHas('usuario', function($q) {
                $q->where('statusAtivoUsuario', 1);
            });
        } elseif ($status === 'inativo') {
            $query->whereHas('usuario', function($q) {
                $q->where('statusAtivoUsuario', 0);
            });
        }
    }

    // --- 2. Busca TODOS os médicos (sem paginação) ---
    $medicos = $query->orderBy('nomeMedico', 'ASC')->get();

    // --- 3. Calcula as métricas baseadas na lista completa ---
    $totalMedicos = $medicos->count();
    $ativosCount = $medicos->where('usuario.statusAtivoUsuario', 1)->count();
    $inativosCount = $medicos->where('usuario.statusAtivoUsuario', 0)->count();
    $novosCount = $medicos->where('created_at', '>=', now()->startOfMonth())->count();

    // --- 4. Retorna a view com todos os dados ---
    return view('unidade.manutencaoMedicos', compact('medicos', 'totalMedicos', 'ativosCount', 'inativosCount', 'novosCount'));
}

    public function create()
    {
        $unidades = Unidade::orderBy('nomeUnidade')->get();
        return view('unidade.cadastroMedico', compact('unidades'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nomeMedico'      => 'required|string|max:255',
                'crmMedico'       => 'required|string|max:20|unique:tbMedico,crmMedico',
                'emailUsuario'    => 'required|email|max:255|unique:tbUsuario,emailUsuario',
                'especialidadeMedico' => 'nullable|string|max:100',
                'unidades'        => 'nullable|array', 
                'unidades.*'      => 'exists:tbUnidade,idUnidadePK',
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
            $medico->especialidadeMedico = $request->input('especialidadeMedico', '');
            $medico->save();

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
                'message' => 'Erro interno ao cadastrar médico: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function edit($id)
    {
        $medico = Medico::with('usuario')->findOrFail($id);
        return view('unidade.editarMedico', compact('medico'));
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

        return redirect()->route('unidade.manutencaoMedicos')->with('success', 'Dados atualizados com sucesso!');
    }

    // 🔥 CORREÇÃO: Rota de redirecionamento é da 'unidade'
    public function toggleStatus($id)
    {
        $medico = Medico::with('usuario')->findOrFail($id);
        $mensagem = '';

        if (!$medico->usuario) {
            return redirect()->route('unidade.manutencaoMedicos')->with('error', 'Este médico não está vinculado a um usuário.');
        }

        $usuario = $medico->usuario;
        $novoStatus = !$usuario->statusAtivoUsuario;
        $usuario->statusAtivoUsuario = $novoStatus;
        $usuario->save();

        $acao = $novoStatus ? 'ativado' : 'desativado';
        $mensagem = "O médico foi {$acao} com sucesso!";

        return redirect()->route('unidade.manutencaoMedicos')->with('success', $mensagem);
    }
}