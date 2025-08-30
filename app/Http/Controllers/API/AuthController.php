<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro público para um novo Paciente.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomeUsuario' => 'required|string|max:255',
            'emailUsuario' => 'required|string|email|max:255|unique:tbUsuario,emailUsuario',
            'senhaUsuario' => 'required|string|min:8|confirmed',
            'cpfPaciente' => 'required|string|max:14|unique:tbPaciente,cpfPaciente',
            'cartaoSusPaciente' => 'required|string|max:20|unique:tbPaciente,cartaoSusPaciente',
            'dataNascPaciente' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            DB::beginTransaction();

            $usuario = Usuario::create([
                // Define o tipo de usuário para a lógica de login
                'nomeUsuario' => 'Paciente', 
                'emailUsuario' => $request->emailUsuario,
                'senhaUsuario' => Hash::make($request->senhaUsuario),
                'statusAtivoUsuario' => true,
            ]);

            $paciente = Paciente::create([
                'id_usuarioFK' => $usuario->idUsuarioPK,
                'nomePaciente' => $request->nomeUsuario, // Usa o nome do formulário
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

    /**
     * (Admin) Pré-cadastra um novo médico no sistema.
     */
    public function adminRegisterMedico(Request $request)
    {
        $request->validate([
            'nomeMedico' => 'required|string|max:255',
            'emailUsuario' => 'required|string|email|max:255|unique:tbUsuario,emailUsuario',
            'crmMedico' => 'required|string|max:255|unique:tbMedico,crmMedico',
            'senhaUsuario' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($request) {
            $usuario = Usuario::create([
                // Define o tipo de usuário para a lógica de login
                'nomeUsuario' => 'Médico', 
                'emailUsuario' => $request->emailUsuario,
                'senhaUsuario' => Hash::make($request->senhaUsuario),
                'statusAtivoUsuario' => true,
            ]);

            Medico::create([
                'id_usuarioFK' => $usuario->idUsuarioPK,
                'nomeMedico' => $request->nomeMedico,
                'crmMedico' => $request->crmMedico,
            ]);
        });

        return response()->json(['message' => 'Médico pré-cadastrado com sucesso!'], 201);
    }

    /**
     * Realiza o login para qualquer tipo de usuário (Paciente ou Médico).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'emailUsuario' => 'required|email',
            'senhaUsuario' => 'required',
        ]);

        $usuario = Usuario::where('emailUsuario', $credentials['emailUsuario'])->first();

        if (! $usuario || ! Hash::check($credentials['senhaUsuario'], $usuario->getAuthPassword())) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
        
        $usuario->tokens()->delete();
        $token = $usuario->createToken('auth_token')->plainTextToken;
        
        // Verifica o tipo de usuário e se o perfil está completo
        $profile = null;
        $profileComplete = false;
        $userType = $usuario->nomeUsuario;

        if ($userType === 'Paciente') {
            $profile = $usuario->paciente;
            if ($profile) {
                // Perfil do paciente é completo se tiver CPF
                $profileComplete = !is_null($profile->cpfPaciente);
            }
        } elseif ($userType === 'Médico') {
            $profile = $usuario->medico;
            if ($profile) {
                // Perfil do médico é completo se tiver especialidade preenchida
                $profileComplete = !is_null($profile->especialidadeMedico);
            }
        }

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario,
            'profile' => $profile,
            'user_type' => $userType,
            'profile_complete' => $profileComplete
        ]);
    }

    /**
     * Realiza o logout do usuário logado.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
}

