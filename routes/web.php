<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/cadastro', function () {
    return view('cadastro');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});


Route::get('/login', function () {
    return view('login');
});

Route::get('/cadastro', function () {
    return view('cadastro');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/pacientes', function () {
    return view('pacientes');
})->name('pacientes');

Route::get('/ajuda', function () {
    return view('ajuda');
})->name('ajuda');

Route::get('/seguranca', function () {
    return view('seguranca');
})->name('seguranca');

Route::get('/logout', function () {
})->name('logout');