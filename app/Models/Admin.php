<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function registrarPaciente(Request $request)
    {
        
        $request->validate([
            'nomeUsuario' => 'required|string|max:255',
            'emailUsuario' => 'required|string|email|max:255|unique:tbUsuario',
            'senhaUsuario' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();
    
            $usuario = Usuario::create([
                'nomeUsuario' => $request->nomeUsuario,
                'emailUsuario' => $request->emailUsuario,
                'senhaUsuario' => Hash::make($request->senhaUsuario),
                'statusAtivoUsuario' => true,
            ]);
    
            Paciente::create([
                'id_usuarioFK' => $usuario->idUsuarioPK,
                'nomePaciente' => $request->nomeUsuario,
            ]);
            
            DB::commit();
    
            return response()->json(['message' => 'Paciente pré-cadastrado com sucesso!'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao pré-cadastrar o paciente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
