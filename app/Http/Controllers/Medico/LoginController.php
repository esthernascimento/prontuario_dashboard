<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Verifica as credenciais do médico e o status do perfil.
     */
    public function checkLogin(Request $request)
    {
        $request->validate([
            'crm' => 'required|string',
            'password' => 'required|string',
        ]);

        // Encontra o usuário pelo CRM do médico associado
        $usuario = Usuario::whereHas('medico', function ($query) use ($request) {
            $query->where('crmMedico', $request->crm);
        })->first();

        if (!$usuario || !Hash::check($request->password, $usuario->senhaUsuario)) {
            return response()->json(['success' => false, 'message' => 'CRM ou Senha inválidos.'], 401);
        }

       
        $medico = $usuario->medico;
        if (is_null($medico->especialidadeMedico)) {
          
            return response()->json([
                'success' => true,
                'complete_profile' => true,
                'message' => 'Login inicial realizado! Por favor, informe sua especialidade para finalizar o cadastro.',
                'user_id' => $usuario->idUsuarioPK 
            ]);
        }

        
        Auth::guard('web')->login($usuario);

        return response()->json([
            'success' => true,
            'redirect' => url('/medico/dashboard') 
        ]);
    }

    /**
     * Completa o cadastro do médico com a especialidade.
     */
    public function completeProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:tbUsuario,idUsuarioPK',
            'especialidade' => 'required|string|max:255',
        ]);

        $usuario = Usuario::find($request->user_id);
        if ($usuario && $usuario->medico) {
            $usuario->medico->update(['especialidadeMedico' => $request->especialidade]);

        
            Auth::guard('web')->login($usuario);

            return response()->json([
                'success' => true,
                'redirect' => url('/medico/dashboard')
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Ocorreu um erro ao atualizar o perfil.'], 500);
    }
}
