<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medico;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Importante para usar os logs
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('loginMedico');
    }

    public function checkLogin(Request $request)
    {
        $request->validate([ 'crm' => 'required|string', 'senha' => 'required|string', ]);
        $medico = Medico::where('crmMedico', $request->crm)->first();

        if ($medico && $medico->usuario && Hash::check($request->senha, $medico->usuario->senhaUsuario)) {
            if (!is_null($medico->especialidadeMedico)) {
                Auth::login($medico->usuario);
                return response()->json(['success' => true, 'profile_complete' => true, 'redirect_url' => route('medico.dashboard') ]);
            }
            return response()->json(['success' => true, 'profile_complete' => false, 'crm' => $medico->crmMedico]);
        }
        return response()->json(['success' => false, 'message' => 'CRM ou Senha inválidos.'], 401);
    }

    public function completeProfile(Request $request)
    {
        Log::info('--- Iniciando completeProfile ---');
        Log::info('Dados recebidos:', $request->all());

        try {
            $validated = $request->validate([
                'crm' => 'required|string|exists:tbMedico,crmMedico',
                'especialidade' => 'required|string|max:255',
            ]);
            Log::info('Validação do perfil passou.', $validated);

            $medico = Medico::where('crmMedico', $request->crm)->firstOrFail();
            $medico->especialidadeMedico = $request->especialidade;
            $medico->save();
            Log::info('Especialidade salva com sucesso para o médico ID: ' . $medico->idMedicoPK);

            Auth::login($medico->usuario);
            Log::info('Login realizado com sucesso após completar o perfil.');

            return response()->json([
                'success' => true,
                'message' => 'Cadastro finalizado com sucesso!',
            ]);

        } catch (ValidationException $e) {
            Log::error('Erro de validação ao completar perfil: ', $e->errors());
            return response()->json(['success' => false, 'message' => 'Dados inválidos.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erro geral ao completar perfil: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocorreu um erro no servidor.'], 500);
        }
    }
}

