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
|
| Aqui definimos as rotas que são acessadas diretamente pelo navegador.
|
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
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/pacientes', function () { return view('pacientes'); })->name('pacientes');
    Route::get('/ajuda', function () { return view('ajuda'); })->name('ajuda');
    Route::get('/seguranca', function () { return view('seguranca'); })->name('seguranca');
    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])->name('alterarSenha');

    // Rotas para o admin gerenciar médicos
    Route::get('/cadastroMedico', function () { return view('cadastroMedico'); })->name('medicos.create');
    Route::post('/medicos/register', [AuthController::class, 'adminRegisterMedico'])->name('medicos.register');
});


// --- ROTAS PROTEGIDAS DO PAINEL MÉDICO ---
// Este grupo garante que apenas um usuário (médico) logado possa acessar estas páginas.
Route::middleware('auth')->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', function () {
        // Lembre-se de criar o arquivo: resources/views/medico/dashboard.blade.php
        return view('medico.dashboard');
    })->name('dashboard');

    // Adicione outras rotas do médico aqui no futuro
});

