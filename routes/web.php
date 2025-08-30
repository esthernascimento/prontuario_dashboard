<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SegurancaController;



Route::get('/', function () {
    return view('index');
});

Route::get('/loginAdm', [LoginController::class, 'showLoginForm'])->name('admin.login');

Route::post('/loginAdm', [LoginController::class, 'login']);


Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');


Route::get('/loginEnfermeiro', function () {
    return view('loginEnfermeiro');
});

Route::get('/loginMedico', function () {
    return view('loginMedico');
});

Route::get('/cadastroAdm', function () {
    return view('cadastroAdm');
});

Route::get('/cadastroEnfermeiro', function () {
    return view('cadastroEnfermeiro');
});

Route::get('/cadastroMedico', function () {
    return view('cadastroMedico');
});

Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');

    Route::get('/pacientes', function () {
        return view('pacientes');
    })->name('admin.pacientes');

    Route::get('/ajuda', function () {
        return view('ajuda');
    })->name('admin.ajuda');

    Route::get('/seguranca', function () {
        return view('seguranca');
    })->name('admin.seguranca');

    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])
    ->name('admin.alterarSenha');

});
