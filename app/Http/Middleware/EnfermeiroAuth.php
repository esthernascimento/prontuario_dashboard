<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class EnfermeiroAuth
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Middleware EnfermeiroAuth executado', [
            'route' => $request->route()->getName(),
            'session_enfermeiro_id' => Session::get('enfermeiro_id')
        ]);
        
        if (!Session::has('enfermeiro_id')) {
            Log::warning('Acesso negado pelo middleware - sem sessão');
            return redirect()->route('enfermeiro.login')
                ->with('error', 'Acesso negado. Faça login como enfermeiro.');
        }

        return $next($request);
    }
}
