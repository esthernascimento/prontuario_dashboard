<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\Enfermeiro\LoginController as EnfermeiroLoginController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\EnfermeiroController;
use App\Http\Controllers\Api\PacienteController;

/*
|--------------------------------------------------------------------------
| Rotas da API
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS (Acessíveis sem login) ---

// Login do médico
Route::post('/medico/login/check', [MedicoLoginController::class, 'login'])->name('api.medico.login.check');

// Completar perfil do médico (especialidade)
Route::post('/medico/profile/complete', [MedicoLoginController::class, 'completarPerfil'])->name('api.medico.profile.complete');


// Login de Admin/Médico/Enfermeiro
Route::post('/login', [AuthController::class, 'login']);

// Rotas de login dinâmico
Route::post('/enfermeiro/login/check', [EnfermeiroLoginController::class, 'checkLogin'])->name('api.enfermeiro.login.check');
Route::post('/enfermeiro/profile/complete', [EnfermeiroLoginController::class, 'completeProfile'])->name('api.enfermeiro.profile.complete');

// ROTAS DO PACIENTE (AGORA PÚBLICAS)
Route::post('/pacientes/login', [PacienteController::class, 'login']);
Route::apiResource('pacientes', PacienteController::class);
// --- ROTAS PROTEGIDAS (Exigem Autenticação) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

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

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

