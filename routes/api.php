<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\Enfermeiro\LoginController as EnfermeiroLoginController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\EnfermeiroController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aqui registramos as rotas da API.
*/

// --- ROTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Fluxo de login dinâmico Médico
Route::post('/medico/login/check', [MedicoLoginController::class, 'checkLogin'])->name('api.medico.login.check');
Route::post('/medico/profile/complete', [MedicoLoginController::class, 'completeProfile'])->name('api.medico.profile.complete');

// Fluxo de login dinâmico Enfermeiro
Route::post('/enfermeiro/login/check', [EnfermeiroLoginController::class, 'checkLogin'])->name('api.enfermeiro.login.check');
Route::post('/enfermeiro/profile/complete', [EnfermeiroLoginController::class, 'completeProfile'])->name('api.enfermeiro.profile.complete');

// --- ROTAS PROTEGIDAS ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin pré-cadastra Médico/Enfermeiro
    Route::post('/admin/register/medico', [AuthController::class, 'adminRegisterMedico'])->name('api.admin.register.medico');
    Route::post('/admin/register/enfermeiro', [AuthController::class, 'adminRegisterEnfermeiro'])->name('api.admin.register.enfermeiro');

    // CRUD Médico
    Route::get('/admin/medicos', [MedicoController::class, 'index']);
    Route::post('/admin/medicos', [MedicoController::class, 'store']);
    Route::put('/admin/medicos/{id}', [MedicoController::class, 'update']);
    Route::delete('/admin/medicos/{id}', [MedicoController::class, 'excluir']);
    Route::post('/admin/medicos/{id}/toggle-status', [MedicoController::class, 'toggleStatus']);

    // CRUD Enfermeiro
    Route::get('/admin/enfermeiro', [EnfermeiroController::class, 'index']);
    Route::post('/admin/enfermeiro', [EnfermeiroController::class, 'store']);
    Route::put('/admin/enfermeiro/{id}', [EnfermeiroController::class, 'update']);
    Route::delete('/admin/enfermeiro/{id}', [EnfermeiroController::class, 'excluir']);
    Route::post('/admin/enfermeiro/{id}/toggle-status', [EnfermeiroController::class, 'toggleStatus']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
