<?php

use App\Http\Controllers\Admin\ConfiguracaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SegurancaController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;

// --- ROTAS PÚBLICAS ---
Route::get('/', function () {
    return view('geral.index');
});

// --- LOGIN ---
Route::get('/loginAdm', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/loginAdm', [LoginController::class, 'login']);
// Logout admin
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');


Route::get('/loginMedico', [MedicoLoginController::class, 'showLoginForm'])->name('medico.login');
Route::get('/loginEnfermeiro', function () {
    return view('loginEnfermeiro');
});

// --- PAINEL ADMIN ---
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    
    // Rota correta para o dashboard que chama o controlador
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pacientes', function () {return view('geral.pacientes');})->name('pacientes');    
    Route::get('/ajuda', function () {return view('geral.ajuda');})->name('ajuda');    
    Route::get('/seguranca', function () {return view('admin.seguranca');})->name('seguranca');    
    Route::get('/configuracoes', [SegurancaController::class, 'configuracoes'])->name('configuracoes');
    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])->name('alterarSenha');

    // Médicos
    Route::get('/cadastroMedico', function () {
        return view('admin.cadastroMedico');
    })->name('medicos.create');
    Route::post('/medicos/register', [AuthController::class, 'adminRegisterMedico'])->name('medicos.register');
    Route::get('/manutencaoMedicos', [MedicoController::class, 'index'])->name('manutencaoMedicos');

    Route::get('/medicos/{id}/editar', [AdminController::class, 'editar'])->name('medicos.editar');
    Route::put('/medicos/{id}', [AdminController::class, 'update'])->name('medicos.update');

    Route::get('/medicos/{id}/excluir', [AdminController::class, 'confirmarExclusao'])->name('medicos.confirmarExclusao');
    Route::delete('/medicos/{id}', [AdminController::class, 'excluir'])->name('medicos.excluir');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('configuracoes', [ConfiguracaoController::class, 'index'])->name('admin.configuracoes');
    Route::post('configuracoes/foto', [ConfiguracaoController::class, 'atualizarFoto'])->name('admin.atualizarFoto');
    Route::post('configuracoes/dados', [ConfiguracaoController::class, 'atualizarDados'])->name('admin.atualizarDados');
});

// --- PAINEL MÉDICO ---
Route::middleware('auth')->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', function () {
        return view('medico.dashboard');
    })->name('dashboard');
});