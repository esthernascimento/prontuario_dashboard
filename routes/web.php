<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SegurancaController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\EnfermeiroController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\Enfermeiro\LoginController as EnfermeiroLoginController;

// --- ROTAS PÚBLICAS ---
Route::get('/', function () {
    return view('geral.index');
});

// --- LOGIN ADMIN ---
Route::get('/loginAdm', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/loginAdm', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

// --- LOGIN MÉDICO ---
Route::get('/loginMedico', [MedicoLoginController::class, 'showLoginForm'])->name('medico.login');

// --- LOGIN ENFERMEIRO ---
Route::get('/loginEnfermeiro', [EnfermeiroLoginController::class, 'showLoginForm'])->name('enfermeiro.login');
Route::post('/loginEnfermeiro', [EnfermeiroLoginController::class, 'login'])->name('enfermeiro.login.post');
Route::post('/logoutEnfermeiro', [EnfermeiroLoginController::class, 'logout'])->name('enfermeiro.logout');

Route::middleware('auth:enfermeiro')->prefix('enfermeiro')->name('enfermeiro.')->group(function () {
    Route::get('/dashboard', function () {
        return view('enfermeiro.dashboard');
    })->name('dashboard');
});

// --- PAINEL ADMIN ---
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Páginas
    Route::get('/pacientes', fn() => view('geral.pacientes'))->name('pacientes');
    Route::get('/ajuda', fn() => view('geral.ajuda'))->name('ajuda');
    Route::get('/seguranca', fn() => view('admin.seguranca'))->name('seguranca');

    Route::get('/configuracoes', [SegurancaController::class, 'configuracoes'])->name('configuracoes');
    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])->name('alterarSenha');

    Route::get('/perfil', [ConfiguracaoController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/update', [ConfiguracaoController::class, 'atualizarPerfil'])->name('perfil.update');

    // CRUD Médicos
    Route::get('/manutencaoMedicos', [MedicoController::class, 'index'])->name('manutencaoMedicos');
    Route::get('/cadastroMedico', [MedicoController::class, 'create'])->name('medicos.create');
    Route::post('/medicos/register', [MedicoController::class, 'store'])->name('medicos.register');
    Route::get('/medicos/{id}/editar', [MedicoController::class, 'editar'])->name('medicos.editar');
    Route::put('/medicos/{id}', [MedicoController::class, 'update'])->name('medicos.update');
    Route::get('/medicos/{id}/excluir', [MedicoController::class, 'confirmarExclusao'])->name('medicos.confirmarExclusao');
    Route::delete('/medicos/{id}', [MedicoController::class, 'excluir'])->name('medicos.excluir');
    Route::post('/medicos/{id}/toggle-status', [MedicoController::class, 'toggleStatus'])->name('medicos.toggleStatus');

    // CRUD Enfermeiros
    Route::get('/manutencaoEnfermeiro', [EnfermeiroController::class, 'index'])->name('manutencaoEnfermeiro');
    Route::get('/cadastroEnfermeiro', [EnfermeiroController::class, 'create'])->name('enfermeiro.create');
    Route::post('/enfermeiro/register', [EnfermeiroController::class, 'store'])->name('enfermeiro.register');
    Route::get('/enfermeiro/{id}/editar', [EnfermeiroController::class, 'editar'])->name('enfermeiro.editar');
    Route::put('/enfermeiro/{id}', [EnfermeiroController::class, 'update'])->name('enfermeiro.update');
    Route::get('/enfermeiro/{id}/excluir', [EnfermeiroController::class, 'confirmarExclusao'])->name('enfermeiro.confirmarExclusao');
    Route::delete('/enfermeiro/{id}', [EnfermeiroController::class, 'excluir'])->name('enfermeiro.excluir');
    Route::post('/enfermeiro/{id}/toggle-status', [EnfermeiroController::class, 'toggleStatus'])->name('enfermeiro.toggleStatus');
});

// --- PAINEL MÉDICO ---
Route::middleware('auth')->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', fn() => view('medico.dashboard'))->name('dashboard');
});
