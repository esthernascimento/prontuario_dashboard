<?php

namespace App\Http\Controllers\Enfermeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enfermeiro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller    
{

    
    public function showLoginForm()
    {
        return view('enfermeiro.login');
    }

    public function login(Request $request)
    {
        Log::info('Tentativa de login do enfermeiro', ['corem' => $request->corem]);
        
        $request->validate([
            'corem' => 'required|string',
            'senha' => 'required|string',
        ]);

        try {
            $enfermeiro = Enfermeiro::with('usuario')
                ->where('corenEnfermeiro', $request->corem)
                ->first();

            Log::info('Enfermeiro encontrado', ['enfermeiro_id' => $enfermeiro ? $enfermeiro->id : null]);

            if (!$enfermeiro) {
                Log::warning('Enfermeiro não encontrado', ['corem' => $request->corem]);
                return back()->withErrors([
                    'corem' => 'COREM não encontrado.'
                ])->withInput();
            }

            if (!$enfermeiro->usuario) {
                Log::error('Usuário não vinculado ao enfermeiro', ['enfermeiro_id' => $enfermeiro->id]);
                return back()->withErrors([
                    'corem' => 'Usuário não encontrado para este enfermeiro.'
                ])->withInput();
            }

            if (!$enfermeiro->usuario->statusAtivoUsuario) {
                Log::warning('Usuário inativo tentando fazer login', ['enfermeiro_id' => $enfermeiro->id]);
                return back()->withErrors([
                    'corem' => 'Usuário inativo. Entre em contato com o administrador.'
                ])->withInput();
            }

            if (Hash::check($request->senha, $enfermeiro->usuario->senhaUsuario)) {
                Log::info('Senha correta, iniciando sessão', ['enfermeiro_id' => $enfermeiro->id]);
                
                Session::flush();
                
                Session::put([
                    'enfermeiro_id' => $enfermeiro->id,
                    'enfermeiro_nome' => $enfermeiro->nomeEnfermeiro,
                    'enfermeiro_coren' => $enfermeiro->corenEnfermeiro,
                    'usuario_id' => $enfermeiro->usuario->idUsuarioPK
                ]);
                
                Auth::guard('enfermeiro')->login($enfermeiro->usuario);
                
                Session::save();
                
                Log::info('Sessão criada com sucesso', [
                    'enfermeiro_id' => Session::get('enfermeiro_id'),
                    'enfermeiro_nome' => Session::get('enfermeiro_nome')
                ]);
                
                return redirect()->route('enfermeiro.dashboard')->with('success', 'Login realizado com sucesso!');
            } else {
                Log::warning('Senha incorreta', ['enfermeiro_id' => $enfermeiro->id]);
                return back()->withErrors([
                    'corem' => 'Senha inválida.'
                ])->withInput();
            }
            
        } catch (\Exception $e) {
            Log::error('Erro durante o login', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'corem' => 'Erro interno. Tente novamente.'
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Log::info('Logout do enfermeiro', ['enfermeiro_id' => Session::get('enfermeiro_id')]);
        
        Auth::guard('enfermeiro')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('enfermeiro.login')->with('success', 'Logout realizado com sucesso!');
    }
}
