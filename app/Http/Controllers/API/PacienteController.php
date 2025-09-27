<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PacienteController extends Controller
{
    /**
     * Lista todos os pacientes.
     */
    public function index()
    {
        return Paciente::orderBy('nome')->paginate(20);
    }

    /**
     * Cria um novo paciente.
     */
    public function store(Request $request)
    {
        // VALIDAÇÃO CORRIGIDA E COMPLETA
        $validator = Validator::make($request->all(), [
            // Dados Básicos (obrigatórios)
            'nome' => 'required|string|min:2',
            'cpf' => ['required', 'string', 'size:11', 'unique:tbPaciente,cpf'],
            'cartao_sus' => ['required', 'string', 'max:20', 'unique:tbPaciente,cartao_sus'],
            'email' => ['required', 'email', 'unique:tbPaciente,email'],
            'senha' => 'required|string|min:6',
            
            // Dados Demográficos (opcionais)
            'data_nasc' => 'nullable|date',
            'nacionalidade' => 'nullable|string',
            'genero' => 'nullable|string',
            'telefone' => 'nullable|string|max:20',
            'caminho_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Endereço (opcionais)
            'logradouro' => 'nullable|string',
            'numero' => 'nullable|string',
            'cep' => 'nullable|string|max:9',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'uf' => 'nullable|string|size:2',
            'estado' => 'nullable|string',
            'pais' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['senha'] = bcrypt($data['senha']);

        if ($request->hasFile('caminho_foto')) {
            $path = $request->file('caminho_foto')->store('fotos_pacientes', 'public');
            $data['caminho_foto'] = $path;
        }

        $paciente = Paciente::create($data);

        return response()->json($paciente, 201);
    }

    /**
     * Exibe um paciente específico.
     */
    public function show($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }
        return $paciente;
    }

    /**
     * Atualiza um paciente.
     */
    public function update(Request $request, $id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }

        // Adicione aqui as regras de validação para o update também
        $data = $request->validate([
            'nome' => 'sometimes|string|min:2',
            'email' => "sometimes|email|unique:tbPaciente,email,{$id}",
            'telefone' => 'sometimes|nullable|string|max:20',
            // ... etc
        ]);

        if ($request->hasFile('caminho_foto')) {
            if ($paciente->caminho_foto) {
                Storage::disk('public')->delete($paciente->caminho_foto);
            }
            $path = $request->file('caminho_foto')->store('fotos_pacientes', 'public');
            $data['caminho_foto'] = $path;
        }

        $paciente->update($data);

        return response()->json($paciente->fresh());
    }

    /**
     * Apaga um paciente (Soft Delete).
     */
    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }
        $paciente->delete();
        return response()->noContent();
    }

    /**
     * Lógica de Login para o Paciente.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'cartao_sus' => 'required|string',
            'senha' => 'required|string',
        ]);

        $paciente = Paciente::where('cartao_sus', $data['cartao_sus'])->first();
        if (!$paciente || !Hash::check($data['senha'], $paciente->senha)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $token = $paciente->createToken('auth-token-paciente')->plainTextToken;

        return response()->json([
            'message' => 'Login bem-sucedido!',
            'paciente' => $paciente,
            'token' => $token
        ]);
    }
}

