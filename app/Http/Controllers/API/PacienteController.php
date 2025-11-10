<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// Imports para as novas funções
use App\Models\Consulta;
use App\Models\Alergia;
use App\Models\Medicamento;
use App\Models\Exame;

class PacienteController extends Controller
{
    public function index()
    {
        return Paciente::orderBy('nomePaciente')->paginate(20);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomePaciente'        => 'required|string|min:2',
            'cpfPaciente'         => ['required', 'string', 'size:11', Rule::unique('tbPaciente', 'cpfPaciente')],
            'dataNascPaciente'    => 'required|date',
            'cartaoSusPaciente'   => ['required', 'string', 'max:20', Rule::unique('tbPaciente', 'cartaoSusPaciente')],
            'generoPaciente'      => 'nullable|string',
            'fotoPaciente'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'telefonePaciente'    => 'nullable|string|max:20',
            'logradouroPaciente'  => 'nullable|string',
            'numLogradouroPaciente' => 'nullable|string',
            'cepPaciente'         => 'nullable|string|max:9',
            'bairroPaciente'      => 'nullable|string',
            'cidadePaciente'      => 'nullable|string',
            'ufPaciente'          => 'nullable|string|size:2',
            'estadoPaciente'      => 'nullable|string',
            'paisPaciente'        => 'nullable|string',
            'statusPaciente'      => 'sometimes|boolean',
            'emailPaciente'       => ['nullable', 'email', Rule::unique('tbPaciente', 'emailPaciente')],
            'senhaPaciente'       => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (!empty($data['senhaPaciente'])) {
            $data['senhaPaciente'] = bcrypt($data['senhaPaciente']);
        }

        $path = '';
        if ($request->hasFile('fotoPaciente')) {

            $path = $request->file('fotoPaciente')->store('images', 'public');
        }
        $data['fotoPaciente'] = $path;

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
            'nomePaciente'        => 'sometimes|string|min:2',
            'emailPaciente'       => ['sometimes', 'nullable', 'email', Rule::unique('tbPaciente', 'emailPaciente')->ignore($paciente->getKey(), $paciente->getKeyName())],
            'generoPaciente'      => 'sometimes|nullable|string',
            'telefonePaciente'    => 'sometimes|nullable|string|max:20',
            'logradouroPaciente'  => 'sometimes|nullable|string',
            'numLogradouroPaciente' => 'sometimes|nullable|string',
            'cepPaciente'         => 'sometimes|nullable|string|max:9',
            'bairroPaciente'      => 'sometimes|nullable|string',
            'cidadePaciente'      => 'sometimes|nullable|string',
            'ufPaciente'          => 'sometimes|nullable|string|size:2',
            'estadoPaciente'      => 'sometimes|nullable|string',
            'paisPaciente'        => 'sometimes|nullable|string',
            'fotoPaciente'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'statusPaciente'      => 'sometimes|boolean',
            'senhaPaciente'       => 'sometimes|nullable|string|min:6',
        ]);


        if ($request->hasFile('fotoPaciente')) {
            $path = $request->file('fotoPaciente')->store('images', 'public');
            $data['fotoPaciente'] = $path;
        }


        if (!empty($data['senhaPaciente'])) {
            $data['senhaPaciente'] = bcrypt($data['senhaPaciente']);
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
            'cpfPaciente' => 'required|string',
            'senhaPaciente'     => 'required|string',
        ]);

        $paciente = Paciente::where('cpfPaciente', $data['cpfPaciente'])->first();

        if (!$paciente || !Hash::check($data['senhaPaciente'], $paciente->senhaPaciente)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $token = $paciente->createToken('auth-token-paciente')->plainTextToken;

        return response()->json([
            'message'  => 'Login bem-sucedido!',
            'paciente' => $paciente,
            'token'    => $token
        ]);
    }

    
    public function me(Request $request)
    {
        // $request->user() é o paciente autenticado pelo token (Sanctum)
        return response()->json($request->user());
    }

    /**
     * Retorna o histórico de consultas (Prontuário) do paciente logado.
     */
    public function getConsultas(Request $request)
    {
        $pacienteId = $request->user()->idPaciente; // Pega o ID do paciente logado

        $consultas = Consulta::where('idPacienteFK', $pacienteId)
                            ->with('medico', 'unidade') // Puxa dados do médico e unidade
                            ->orderBy('dataConsulta', 'desc')
                            ->get();
        
        return response()->json($consultas);
    }

    /**
     * Retorna as alergias do paciente logado.
     */
    public function getAlergias(Request $request)
    {
        $pacienteId = $request->user()->idPaciente;
        $alergias = Alergia::where('idPacienteFK', $pacienteId)->get();
        return response()->json($alergias);
    }

    /**
     * Retorna os medicamentos prescritos ao paciente logado.
     */
    public function getMedicamentos(Request $request)
    {
        $pacienteId = $request->user()->idPaciente;
        $medicamentos = Medicamento::where('idPacienteFK', $pacienteId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        return response()->json($medicamentos);
    }

    /**
     * Retorna os exames solicitados ao paciente logado.
     */
    public function getExames(Request $request)
    {
        $pacienteId = $request->user()->idPaciente;
        $exames = Exame::where('idPacienteFK', $pacienteId)
                       ->orderBy('dataExame', 'desc')
                       ->get();
        return response()->json($exames);
    }

    /**
     * Rota de Logout (Requer Token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}