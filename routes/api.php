<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Medico\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui registramos as rotas da sua API.
|
*/

// --- ROTAS PÚBLICAS ---
// Não precisam de autenticação para serem acessadas.

// Registro público de Pacientes (se aplicável)
Route::post('/register', [AuthController::class, 'register']);

// Login de Usuários (Pacientes/Médicos via pré-cadastro)
Route::post('/login', [AuthController::class, 'login']);

// Rotas para o fluxo de login dinâmico do Médico
Route::post('/medico/login/check', [LoginController::class, 'checkLogin']);
Route::post('/medico/profile/complete', [LoginController::class, 'completeProfile']);


// --- ROTAS PROTEGIDAS ---
// Exigem um token de autenticação (usuário deve estar logado).
Route::middleware('auth:sanctum')->group(function () {
    // Rota padrão do Laravel para obter dados do usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota para o Admin pré-cadastrar um novo médico
    Route::post('/admin/register/medico', [AuthController::class, 'registrarMedico']);

    // Rota para fazer logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

