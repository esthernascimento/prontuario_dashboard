<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SegurancaController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Enfermeiro\LoginController as EnfermeiroLoginController;

// --- ROTAS PÚBLICAS ---
Route::get('/', function () {
    return view('geral.index');
});

// --- LOGIN ---
Route::get('/loginAdm', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/loginAdm', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('/loginMedico', [MedicoLoginController::class, 'showLoginForm'])->name('medico.login');



// Rotas públicas do enfermeiro
Route::get('/loginEnfermeiro', [EnfermeiroLoginController::class, 'showLoginForm'])->name('enfermeiro.login');
Route::post('/loginEnfermeiro', [EnfermeiroLoginController::class, 'login'])->name('enfermeiro.login.post');
Route::post('/logoutEnfermeiro', [EnfermeiroLoginController::class, 'logout'])->name('enfermeiro.logout');

// Rotas protegidas do enfermeiro
Route::middleware('auth:enfermeiro')->prefix('enfermeiro')->name('enfermeiro.')->group(function () {
    Route::get('/dashboard', function () {
        return view('enfermeiro.dashboard');
    })->name('dashboard');
});


// --- PAINEL ADMIN ---
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Páginas gerais
    Route::get('/pacientes', function () { return view('geral.pacientes'); })->name('pacientes');
    Route::get('/ajuda', function () { return view('geral.ajuda'); })->name('ajuda');

    // Segurança e configurações
    Route::get('/seguranca', function () { return view('admin.seguranca'); })->name('seguranca');
    Route::get('/configuracoes', [SegurancaController::class, 'configuracoes'])->name('configuracoes');
    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])->name('alterarSenha');

    // Perfil do administrador
    Route::get('/perfil', [ConfiguracaoController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/update', [ConfiguracaoController::class, 'atualizarPerfil'])->name('perfil.update');

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

// --- PAINEL MÉDICO ---
Route::middleware('auth')->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', function () {
        return view('medico.dashboard');
    })->name('dashboard');
    
});

Route::post('/admin/medicos/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.medicos.toggleStatus');
    