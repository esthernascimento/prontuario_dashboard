<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- Importação de Todos os Controllers ---
// Autenticação e Gestão de Utilizadores
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\Enfermeiro\LoginController as EnfermeiroLoginController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\EnfermeiroController;

// API do Paciente e Prontuário
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\ConsultaController;
use App\Http\Controllers\Api\MedicamentoController;
use App\Http\Controllers\Api\ExameController;
use App\Http\Controllers\Api\AlergiaController;
use App\Http\Controllers\Api\UnidadeController;
use App\Http\Controllers\Api\ProntuarioController;



// --- ROTAS PÚBLICAS (Acessíveis sem login) ---

// Login de Admin/Médico/Enfermeiro (via tbUsuario)
Route::post('/login', [AuthController::class, 'login']);

// Rotas de login dinâmico
Route::post('/enfermeiro/login/check', [EnfermeiroLoginController::class, 'checkLogin'])->name('api.enfermeiro.login.check');
Route::post('/enfermeiro/profile/complete', [EnfermeiroLoginController::class, 'completeProfile'])->name('api.enfermeiro.profile.complete');

// Rotas de login e registo de Pacientes (via tbPaciente, para a app mobile)
Route::post('/pacientes/login', [PacienteController::class, 'login']);
Route::post('/pacientes', [PacienteController::class, 'store']);
Route::get('/pacientes', [PacienteController::class, 'index']);
Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);




// --- ROTAS PROTEGIDAS (Exigem autenticação com token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Rota para obter dados do utilizador logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota para fazer logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- ROTAS DO PRONTUÁRIO (Acessíveis pelo paciente/médico logado) ---
    
    // Rota principal para obter o prontuário completo de um paciente
    Route::get('/prontuarios/{idPaciente}', [ProntuarioController::class, 'show']);
    // Rota para abrir um novo prontuário
    Route::post('/prontuarios', [ProntuarioController::class, 'store']);

    // CRUD para as partes do prontuário
    Route::apiResource('consultas', ConsultaController::class);
    Route::apiResource('medicamentos', MedicamentoController::class);
    Route::apiResource('exames', ExameController::class);
    Route::apiResource('alergias', AlergiaController::class);
    Route::apiResource('unidades', UnidadeController::class);




    // --- ROTAS DE ADMINISTRAÇÃO ---
    
    // Admin pré-cadastra Médico/Enfermeiro
    Route::post('/admin/register/medico', [AuthController::class, 'adminRegisterMedico'])->name('api.admin.register.medico');
    Route::post('/admin/register/enfermeiro', [AuthController::class, 'adminRegisterEnfermeiro'])->name('api.admin.register.enfermeiro');

    // CRUD Médico (Gerido pelo Admin)
    Route::get('/admin/medicos', [MedicoController::class, 'index']);
    Route::post('/admin/medicos', [MedicoController::class, 'store']);
    Route::put('/admin/medicos/{id}', [MedicoController::class, 'update']);
    Route::delete('/admin/medicos/{id}', [MedicoController::class, 'excluir']);
    Route::post('/admin/medicos/{id}/toggle-status', [MedicoController::class, 'toggleStatus']);

    // CRUD Enfermeiro (Gerido pelo Admin)
    Route::get('/admin/enfermeiro', [EnfermeiroController::class, 'index']);
    Route::post('/admin/enfermeiro', [EnfermeiroController::class, 'store']);
    Route::put('/admin/enfermeiro/{id}', [EnfermeiroController::class, 'update']);
    Route::delete('/admin/enfermeiro/{id}', [EnfermeiroController::class, 'excluir']);
    Route::post('/admin/enfermeiro/{id}/toggle-status', [EnfermeiroController::class, 'toggleStatus']);
});

