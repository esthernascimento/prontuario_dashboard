<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Paciente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomePaciente' => 'required|string|max:255',
            'emailUsuario' => 'required|string|email|max:255|unique:tbUsuario',
            'senhaUsuario' => 'required|string|min:8|confirmed',
            'cpfPaciente' => 'required|string|max:14|unique:tbPaciente',
            'cartaoSusPaciente' => 'required|string|max:20|unique:tbPaciente',
            'dataNascPaciente' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $usuario = Usuario::create([
                'emailUsuario' => $request->emailUsuario,
                'senhaUsuario' => Hash::make($request->senhaUsuario),
                'statusAtivoUsuario' => true,
            ]);

            $paciente = Paciente::create([
                'id_usuarioFK' => $usuario->idUsuarioPK,
                'nomePaciente' => $request->nomePaciente,
                'cpfPaciente' => $request->cpfPaciente,
                'cartaoSusPaciente' => $request->cartaoSusPaciente,
                'dataNascPaciente' => $request->dataNascPaciente,
                'logradouroPaciente' => $request->logradouroPaciente,
                'cidadePaciente' => $request->cidadePaciente,
                'ufPaciente' => $request->ufPaciente,
                'cepPaciente' => $request->cepPaciente,
                'alergiasPaciente' => $request->alergiasPaciente,
            ]);

            DB::commit();

            $token = $usuario->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário registrado com sucesso!',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'usuario' => $usuario,
                'paciente' => $paciente,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao registrar o usuário.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'emailUsuario' => 'required|email',
            'senhaUsuario' => 'required',
        ]);

        $usuario = Usuario::where('emailUsuario', $credentials['emailUsuario'])->first();

        if (! $usuario || ! Hash::check($credentials['senhaUsuario'], $usuario->senhaUsuario)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
        
        $usuario->tokens()->delete();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario->load('paciente')
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
}
