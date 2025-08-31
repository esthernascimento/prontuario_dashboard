<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SegurancaController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| Rotas Web
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS GERAIS ---
Route::get('/', function () {
    return view('index');
});

// --- ROTAS DE LOGIN ---
Route::get('/loginAdm', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/loginAdm', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('/loginMedico', [MedicoLoginController::class, 'showLoginForm'])->name('medico.login');

Route::get('/loginEnfermeiro', function () {
    return view('loginEnfermeiro');
});

// --- ROTAS PROTEGIDAS DO PAINEL ADMIN ---
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/pacientes', fn() => view('pacientes'))->name('pacientes');
    Route::get('/ajuda', fn() => view('ajuda'))->name('ajuda');
    Route::get('/seguranca', fn() => view('seguranca'))->name('seguranca');
    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])->name('alterarSenha');

    // --- ROTAS DE MÉDICOS ---
    Route::get('/manutencaoMedicos', fn() => view('manutencaoMedicos'))->name('manutencaoMedicos');
    Route::get('/cadastrarMedico', fn() => view('cadastrarMedico'))->name('cadastrarMedicos');
    Route::post('/medicos/register', [AuthController::class, 'adminRegisterMedico'])->name('medicos.register');
    Route::get('/editarMedico', fn() => view('editarMedico'))->name('editarMedicos');
    Route::get('/excluirMedico', fn() => view('excluirMedico'))->name('excluirMedicos');
    Route::get('/editarMedico/{id}', fn($id) => view('editarMedico', ['id' => $id]))->name('editarMedico');

});

// --- ROTAS PROTEGIDAS DO PAINEL MÉDICO ---
Route::middleware('auth')->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', fn() => view('medico.dashboard'))->name('dashboard');
});
