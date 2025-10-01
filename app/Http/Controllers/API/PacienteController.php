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
    public function index()
    {
        return Paciente::orderBy('nomePaciente')->paginate(20);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|min:2',
            'cpf' => ['required', 'string', 'size:11', 'unique:pacientes,cpf'],
            'data_nasc' => 'nullable|date',
            'cartao_sus' => ['required', 'string', 'max:20', 'unique:pacientes,cartao_sus'],
            'nacionalidade' => 'nullable|string',
            'genero' => 'nullable|string',
            'caminho_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'telefone' => 'nullable|string|max:20',
            'logradouro' => 'nullable|string',
            'numero' => 'nullable|string',
            'cep' => 'nullable|string|max:9',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'uf' => 'nullable|string|size:2',
            'estado' => 'nullable|string',
            'pais' => 'nullable|string',
            'email' => ['required', 'email', 'unique:pacientes,email'],
            'senha' => 'required|string|min:6',
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

    public function show($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }
        return $paciente;
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }

        $data = $request->validate([
            'nome' => 'sometimes|string|min:2',
            'email' => "sometimes|email|unique:pacientes,email,{$id}",
            'telefone' => 'sometimes|nullable|string|max:20',
            'logradouro' => 'sometimes|nullable|string',
            'numero' => 'sometimes|nullable|string',
            'cep' => 'sometimes|nullable|string|max:9',
            'bairro' => 'sometimes|nullable|string',
            'cidade' => 'sometimes|nullable|string',
            'uf' => 'sometimes|nullable|string|size:2',
            'estado' => 'sometimes|nullable|string',
            'pais' => 'sometimes|nullable|string',
            'caminho_foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
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

    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado.'], 404);
        }
        $paciente->delete();
        return response()->noContent();
    }

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

