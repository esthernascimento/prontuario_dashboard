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
use App\Http\Controllers\Api\ProntuarioController;

// ADICIONADO: Importação do controller de Unidades para o Admin
use App\Http\Controllers\Admin\UnidadeController;



/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS (Acessíveis sem login)
|--------------------------------------------------------------------------
*/

// Login geral (Admin / Médico / Enfermeiro)
Route::post('/login', [AuthController::class, 'login']);

// Rotas de login dinâmico
Route::post('/enfermeiro/login/check', [EnfermeiroLoginController::class, 'checkLogin'])->name('api.enfermeiro.login.check');
Route::post('/enfermeiro/profile/complete', [EnfermeiroLoginController::class, 'completeProfile'])->name('api.enfermeiro.profile.complete');

// Rotas públicas (para cadastro e primeiro update)
Route::post('/pacientes/login', [PacienteController::class, 'login']);
Route::post('/pacientes', [PacienteController::class, 'store']);
Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
Route::get('/pacientes', [PacienteController::class, 'index']);



/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS DO PACIENTE (Token obrigatório)
|--------------------------------------------------------------------------
|
| Essas rotas usam o guard 'paciente' definido no config/auth.php
| O app mobile deve enviar o Bearer Token no header Authorization.
|
*/
Route::middleware(['auth:sanctum', 'auth:paciente'])->group(function () {

    // Perfil do paciente logado
    Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);
    Route::post('/pacientes/logout', [PacienteController::class, 'logout']);

    // Prontuário do paciente
    Route::get('/paciente/consultas', [PacienteController::class, 'getConsultas']);
    Route::get('/paciente/alergias', [PacienteController::class, 'getAlergias']);
    Route::get('/paciente/medicamentos', [PacienteController::class, 'getMedicamentos']);
    Route::get('/paciente/exames', [PacienteController::class, 'getExames']);
});

/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS GERAIS (Admin / Médico / Enfermeiro)
|--------------------------------------------------------------------------
| Usam o login do AuthController
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // Dados do utilizador autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout (Esta rota estava duplicada, mas é ok manter)
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | PRONTUÁRIO (Rotas genéricas)
    |--------------------------------------------------------------------------
    */
    Route::get('/prontuarios/{idPaciente}', [ProntuarioController::class, 'show']);
    Route::post('/prontuarios', [ProntuarioController::class, 'store']);

    // CRUD para partes do prontuário
    Route::apiResource('consultas', ConsultaController::class);
    Route::apiResource('medicamentos', MedicamentoController::class);
    Route::apiResource('exames', ExameController::class);
    Route::apiResource('alergias', AlergiaController::class);


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRAÇÃO (Rotas do Painel de Admin)
    |--------------------------------------------------------------------------
    */

    // Registro de Médico e Enfermeiro pelo Admin
    Route::post('/admin/register/medico', [AuthController::class, 'adminRegisterMedico'])->name('api.admin.register.medico');
    Route::post('/admin/register/enfermeiro', [AuthController::class, 'adminRegisterEnfermeiro'])->name('api.admin.register.enfermeiro');

    // CRUD Médico
    Route::get('/admin/medicos', [MedicoController::class, 'index']);
    Route::post('/admin/medicos', [MedicoController::class, 'store']);
    Route::put('/admin/medicos/{id}', [MedicoController::class, 'update']);
    Route::delete('/admin/medicos/{id}', [MedicoController::class, 'excluir']);
    Route::post('/admin/medicos/{id}/toggle-status', [MedicoController::class, 'toggleStatus']);
    Route::post('/admin/medicos/{medico}/unidades', [MedicoController::class, 'syncUnidades']);

    // CRUD Enfermeiro
    Route::get('/admin/enfermeiro', [EnfermeiroController::class, 'index']);
    Route::post('/admin/enfermeiro', [EnfermeiroController::class, 'store']);
    Route::put('/admin/enfermeiro/{id}', [EnfermeiroController::class, 'update']);
    Route::delete('/admin/enfermeiro/{id}', [EnfermeiroController::class, 'excluir']);
    Route::post('/admin/enfermeiro/{id}/toggle-status', [EnfermeiroController::class, 'toggleStatus']);
    Route::post('/admin/enfermeiro/{enfermeiro}/unidades', [EnfermeiroController::class, 'syncUnidades']);

    // CRUD Unidade
    Route::get('/admin/unidades', [UnidadeController::class, 'index']);
    Route::post('/admin/unidades', [UnidadeController::class, 'store']);
    Route::get('/admin/unidades/{unidade}', [UnidadeController::class, 'show']);
    Route::put('/admin/unidades/{unidade}', [UnidadeController::class, 'update']);
    Route::delete('/admin/unidades/{unidade}', [UnidadeController::class, 'destroy']);
});
