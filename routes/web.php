<?php

use Illuminate\Support\Facades\Route;

// ===================================================================================
// --- IMPORTAÇÃO DE CONTROLLERS ---
// ===================================================================================

// --------------------- ADMIN ---------------------
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\SegurancaController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\EnfermeiroController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UnidadeController;
use App\Http\Controllers\Admin\SuporteController;
use App\Http\Controllers\Admin\RecepcionistaController;

// --------------------- PACIENTE ---------------------
use App\Http\Controllers\Paciente\PacienteController as AdminPacienteController;

// --------------------- MÉDICO ---------------------
use App\Http\Controllers\Medico\LoginController as MedicoLoginController;
use App\Http\Controllers\Medico\MedicoDashboardController;
use App\Http\Controllers\Medico\MedicoConfiguracaoController;
use App\Http\Controllers\Medico\MedicoSegurancaController;
use App\Http\Controllers\Medico\MedicoProntuarioController;

// --------------------- ENFERMEIRO ---------------------
use App\Http\Controllers\Enfermeiro\LoginController as EnfermeiroLoginController;
use App\Http\Controllers\Enfermeiro\DashboardController as EnfermeiroDashboardController;
use App\Http\Controllers\Enfermeiro\ConfiguracaoController as ConfiguracaoEnfermeiroController;
use App\Http\Controllers\Enfermeiro\SegurancaController as SegurancaEnfermeiroController;
use App\Http\Controllers\Enfermeiro\ProntuarioController;

// --------------------- RECEPCIONISTA ---------------------
use App\Http\Controllers\Recepcionista\LoginController as RecepcionistaLoginController;
use App\Http\Controllers\Recepcionista\RecepcionistaDashboardController;
use App\Http\Controllers\Recepcionista\AcolhimentoController;


// ===================================================================================
// --- ROTAS PÚBLICAS ---
// ===================================================================================

Route::get('/', fn() => view('geral.index'))->name('home');

// --- ADICIONADO MIDDLEWARE 'WEB' PARA GARANTIR A SESSÃO E CSRF ---
Route::middleware('web')->group(function () {
    // ----------------- LOGIN ADMIN -----------------
    Route::get('/loginAdm', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/loginAdm', [AdminLoginController::class, 'login']);
    Route::post('/logoutAdm', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // ----------------- LOGIN MÉDICO -----------------
    Route::get('/loginMedico', [MedicoLoginController::class, 'showLoginForm'])->name('medico.login');
    Route::post('/loginMedico', [MedicoLoginController::class, 'login'])->name('medico.login.submit');
    Route::post('/medico/profile/complete', [MedicoLoginController::class, 'completarPerfil'])->name('api.medico.profile.complete');
    Route::post('/medico/login/check', [MedicoLoginController::class, 'login'])->name('api.medico.login.check');

    // ----------------- LOGIN ENFERMEIRO -----------------
    Route::get('/enfermeiro/login', [EnfermeiroLoginController::class, 'showLoginForm'])->name('enfermeiro.login');
    Route::post('/enfermeiro/login', [EnfermeiroLoginController::class, 'login'])->name('enfermeiro.login.submit');

    // ----------------- LOGIN RECEPCIONISTA (CORRIGIDO) -----------------
    Route::get('/loginRecepcionista', [RecepcionistaLoginController::class, 'showLoginForm'])
         ->name('recepcionista.login');
    Route::post('/loginRecepcionista', [RecepcionistaLoginController::class, 'login'])
         ->name('recepcionista.login.submit');
});
// --- FIM DO GRUPO DE MIDDLEWARE 'WEB' ---


// ===================================================================================
// --- ROTAS PROTEGIDAS DO ADMIN ---
// ===================================================================================
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard e páginas gerais
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/ajuda', fn() => view('geral.ajuda'))->name('ajuda');
    Route::post('/ajuda/enviar', [SuporteController::class, 'enviarMensagem'])->name('ajuda.enviar');

    // Perfil e segurança
    Route::get('/perfil', [ConfiguracaoController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/update', [ConfiguracaoController::class, 'atualizarPerfil'])->name('perfil.update');
    Route::get('/seguranca', [SegurancaController::class, 'showAlterarSenhaForm'])->name('seguranca');
    Route::post('/alterar-senha', [SegurancaController::class, 'alterarSenha'])->name('alterarSenha');
    Route::get('/configuracoes', [SegurancaController::class, 'configuracoes'])->name('configuracoes');

    // Gestão de pacientes
    Route::post('pacientes/{paciente}/toggle-status', [AdminPacienteController::class, 'toggleStatus'])->name('pacientes.toggleStatus');
    Route::resource('pacientes', AdminPacienteController::class);

    // CRUD Médicos
    Route::get('/manutencaoMedicos', [MedicoController::class, 'index'])->name('manutencaoMedicos');
    Route::get('/cadastroMedico', [MedicoController::class, 'create'])->name('medicos.create');
    Route::post('/medicos/register', [MedicoController::class, 'store'])->name('medicos.register');
    Route::get('/medicos/{id}/editar', [MedicoController::class, 'editar'])->name('medicos.editar');
    Route::put('/medicos/{id}', [MedicoController::class, 'update'])->name('medicos.update');
    Route::get('/medicos/{id}/excluir', [MedicoController::class, 'confirmarExclusao'])->name('medicos.confirmarExclusao');
    Route::delete('/medicos/{id}', [MedicoController::class, 'excluir'])->name('medicos.excluir');
    Route::post('/medicos/{id}/toggle-status', [MedicoController::class, 'toggleStatus'])->name('medicos.toggleStatus');
    Route::post('/medicos/{medico}/unidades', [MedicoController::class, 'syncUnidades'])->name('medicos.syncUnidades');

    // CRUD Enfermeiros
    Route::get('/manutencaoEnfermeiro', [EnfermeiroController::class, 'index'])->name('manutencaoEnfermeiro');
    Route::get('/cadastroEnfermeiro', [EnfermeiroController::class, 'create'])->name('enfermeiro.create');
    Route::post('/enfermeiro/register', [EnfermeiroController::class, 'store'])->name('enfermeiro.register');
    Route::get('/enfermeiro/{id}/editar', [EnfermeiroController::class, 'editar'])->name('enfermeiro.editar');
    Route::put('/enfermeiro/{id}', [EnfermeiroController::class, 'update'])->name('enfermeiro.update');
    Route::get('/enfermeiro/{id}/excluir', [EnfermeiroController::class, 'confirmarExclusao'])->name('enfermeiro.confirmarExclusao');
    Route::delete('/enfermeiro/{id}', [EnfermeiroController::class, 'excluir'])->name('enfermeiro.excluir');
    Route::post('/enfermeiro/{id}/toggle-status', [EnfermeiroController::class, 'toggleStatus'])->name('enfermeiro.toggleStatus');
    Route::post('/enfermeiro/{enfermeiro}/unidades', [EnfermeiroController::class, 'syncUnidades'])->name('enfermeiro.syncUnidades');

    // CRUD Unidades
    Route::get('/unidades', [UnidadeController::class, 'index'])->name('unidades.index');
    Route::get('/unidades/create', [UnidadeController::class, 'create'])->name('unidades.create');
    Route::post('/unidades', [UnidadeController::class, 'store'])->name('unidades.store');
    Route::get('/unidades/{unidade}/edit', [UnidadeController::class, 'edit'])->name('unidades.edit');
    Route::put('/unidades/{unidade}', [UnidadeController::class, 'update'])->name('unidades.update');
    Route::delete('/unidades/{unidade}', [UnidadeController::class, 'destroy'])->name('unidades.destroy');
    Route::post('/unidades/{id}/toggle-status', [UnidadeController::class, 'toggleStatus'])->name('unidades.toggleStatus');

    // ADICIONADO: CRUD RECEPCIONISTAS
    Route::get('/recepcionistas', [RecepcionistaController::class, 'index'])->name('recepcionistas.index');
    Route::get('/recepcionistas/create', [RecepcionistaController::class, 'create'])->name('recepcionistas.create');
    Route::post('/recepcionistas', [RecepcionistaController::class, 'store'])->name('recepcionistas.store');
    Route::get('/recepcionistas/{recepcionista}/edit', [RecepcionistaController::class, 'edit'])->name('recepcionistas.edit');
    Route::put('/recepcionistas/{recepcionista}', [RecepcionistaController::class, 'update'])->name('recepcionistas.update');
    Route::delete('/recepcionistas/{recepcionista}', [RecepcionistaController::class, 'destroy'])->name('recepcionistas.destroy');
});

// ===================================================================================
// --- ROTAS PROTEGIDAS DO MÉDICO ---
// ===================================================================================
Route::middleware('auth')->prefix('medico')->name('medico.')->group(function () {
    Route::get('/dashboard', [MedicoDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [MedicoLoginController::class, 'logout'])->name('logout');
    
    Route::get('/perfil', [MedicoConfiguracaoController::class, 'perfil'])->name('perfil');
    Route::put('/perfil/update', [MedicoConfiguracaoController::class, 'atualizarPerfil'])->name('perfil.update');
    
    Route::get('/seguranca', [MedicoSegurancaController::class, 'showAlterarSenhaForm'])->name('seguranca');
    Route::post('/alterar-senha', [MedicoSegurancaController::class, 'alterarSenha'])->name('alterarSenha');
    Route::get('/ajuda', fn() => view('medico.ajudaMedico'))->name('ajuda');

    // Prontuário
    Route::get('/prontuario', [MedicoProntuarioController::class, 'index'])->name('prontuario');
    Route::get('/cadastrar-prontuario/{id}', [MedicoProntuarioController::class, 'create'])->name('cadastrarProntuario');
    Route::post('/cadastrar-prontuario/{id}', [MedicoProntuarioController::class, 'store'])->name('prontuario.store');
    Route::get('/prontuario/editar/{id}', [MedicoProntuarioController::class, 'edit'])->name('prontuario.edit');
    Route::put('/prontuario/atualizar/{id}', [MedicoProntuarioController::class, 'update'])->name('prontuario.update');
    Route::delete('/prontuario/deletar/{id}', [MedicoProntuarioController::class, 'destroy'])->name('prontuario.destroy');
    Route::get('/visualizar-prontuario/{id}', [MedicoProntuarioController::class, 'show'])->name('visualizarProntuario');
    Route::get('/prontuario/{id}', [MedicoProntuarioController::class, 'show'])->name('paciente.prontuario');
});

// ===================================================================================
// --- ROTAS PROTEGIDAS DO ENFERMEIRO ---
// ===================================================================================
Route::prefix('enfermeiro')->name('enfermeiro.')->group(function () {

    Route::middleware('auth:enfermeiro')->group(function () {

        Route::get('/dashboard', [EnfermeiroDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [EnfermeiroLoginController::class, 'logout'])->name('logout');
        Route::get('/perfil', [ConfiguracaoEnfermeiroController::class, 'perfil'])->name('perfil');
        
        Route::put('/perfil/update', [ConfiguracaoEnfermeiroController::class, 'atualizarPerfil'])->name('perfil.update');
        
        Route::get('/seguranca', [SegurancaEnfermeiroController::class, 'showAlterarSenhaForm'])->name('seguranca');
        Route::post('/alterar-senha', [SegurancaEnfermeiroController::class, 'alterarSenha'])->name('alterarSenha');

        // Pacientes
        Route::get('/paciente', fn() => view('enfermeiro.pacientes'))->name('pacientes');
        Route::get('/ajuda', fn() => view('enfermeiro.ajuda'))->name('ajuda');

        // Prontuário / Anotações
        Route::get('/prontuario', [ProntuarioController::class, 'index'])->name('prontuario');
        Route::get('/visualizar-prontuario/{id}', [ProntuarioController::class, 'show'])->name('visualizarProntuario');
        Route::get('/prontuario/{id}/anotacao/criar', [ProntuarioController::class, 'create'])->name('anotacao.create');
        Route::post('/prontuario/{id}/anotacao/salvar', [ProntuarioController::class, 'store'])->name('anotacao.store');
        Route::get('/prontuario/anotacao/editar/{id}', [ProntuarioController::class, 'edit'])->name('anotacao.edit');
        Route::put('/prontuario/anotacao/atualizar/{id}', [ProntuarioController::class, 'update'])->name('anotacao.update');
        Route::delete('/prontuario/anotacao/deletar/{id}', [ProntuarioController::class, 'destroy'])->name('anotacao.destroy');
    });
});

// ===================================================================================
// --- ROTAS DO RECEPCIONISTA (PROTEGIDAS) ---
// ===================================================================================

// --- Rotas Protegidas (Só acessa depois de logar) ---
Route::middleware(['web', 'auth:recepcionista'])->prefix('recepcionista')->name('recepcionista.')->group(function () {
    
    // A "dashboard" principal leva direto para o formulário de Acolhimento
    Route::get('/dashboard', [AcolhimentoController::class, 'create'])->name('dashboard');

    // Rota para ONDE o formulário de acolhimento envia os dados
    Route::post('/acolhimento/salvar', [AcolhimentoController::class, 'store'])->name('acolhimento.store');
    
    // Rota que o AJAX da view de acolhimento usa para buscar pacientes
    Route::get('/pacientes/buscar', [AdminPacienteController::class, 'buscar'])->name('pacientes.buscar');

    // Rota de Logout
    Route::post('/logout', [RecepcionistaLoginController::class, 'logout'])->name('logout');

    Route::get('/perfil', [RecepcionistaConfiguracaoController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/atualizar', [RecepcionistaConfiguracaoController::class, 'atualizarPerfil'])->name('atualizarPerfil');
    
});

// (As suas rotas /dashboardRecepcionista e /loginRecepcionista antigas foram
// substituídas pelas rotas acima, que usam os Controllers corretos e
// estão agora protegidas e funcionais)

