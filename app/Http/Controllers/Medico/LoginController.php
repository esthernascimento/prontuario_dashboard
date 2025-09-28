<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Medico;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('medico.loginMedico');
    }

    public function login(Request $request)
    {
        $request->validate([
            'crm'   => 'required|string',
            'senha' => 'required|string',
        ]);

        // Buscar médico pelo CRM e trazer o usuário relacionado
        $medico = Medico::with('usuario')
            ->where('crmMedico', $request->crm)
            ->first();

        if (!$medico || !$medico->usuario) {
            return response()->json([
                'message' => 'CRM inválido.',
            ], 401);
        }

        // Validar senha do usuário vinculado
        if (!Hash::check($request->senha, $medico->usuario->senhaUsuario)) {
            return response()->json([
                'message' => 'Senha incorreta.',
            ], 401);
        }

        // Autenticar o usuário dono do médico
        Auth::login($medico->usuario);

        // Verificar perfil completo (ex: especialidade preenchida)
        if (empty($medico->especialidadeMedico)) {
            return response()->json([
                'profile_complete' => false,
                'message' => 'Perfil incompleto. Informe sua especialidade para continuar.',
            ]);
        }

        return response()->json([
            'profile_complete' => true,
            'redirect_url' => route('medico.dashboard'),
        ]);
    }

    public function completarPerfil(Request $request)
    {
        $request->validate([
            'crm' => 'required|string',
            'especialidade' => 'required|string',
        ]);

        $medico = Medico::where('crmMedico', $request->crm)->first();

        if (!$medico) {
            return response()->json([
                'message' => 'Médico não encontrado.',
            ], 404);
        }

        $medico->especialidadeMedico = $request->especialidade;
        $medico->save();

        return response()->json([
            'profile_complete' => true,
            'redirect_url' => route('medico.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
