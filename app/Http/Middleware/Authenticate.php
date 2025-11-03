<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param array|string|null $guards
     */
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // --- Lógica de Redirecionamento por Guard ---
        // $guards é uma propriedade interna que o Laravel usa para saber qual guard falhou na autenticação
        $guards = empty($this->guards) ? [null] : $this->guards;

        foreach ($guards as $guard) {
            switch ($guard) {
                case 'admin':
                    return route('admin.login');
                case 'medico':
                    return route('medico.login');
                case 'enfermeiro':
                    return route('enfermeiro.login');
                case 'recepcionista':
                    return route('recepcionista.login');
                case 'unidade':
                    return route('unidade.login'); // Redireciona para a rota correta!
                default:
                    // Se for o guard padrão ou não especificado, retorna a rota 'home' ou 'login' padrão, se existir
                    return route('home'); 
            }
        }

        // Fallback genérico, se necessário
        return route('home');
    }
}