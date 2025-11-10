<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        // 🔥 CORREÇÃO APLICADA AQUI:
        'medico' => [
            'driver' => 'session',
            'provider' => 'users', // Alterado de 'medicos' para 'users'
        ],

        'enfermeiro' => [
            'driver' => 'session',
            'provider' => 'enfermeiros',
        ],

        'recepcionista' => [
            'driver' => 'session',
            'provider' => 'recepcionistas',
        ],

        'unidade' => [
            'driver' => 'session',
            'provider' => 'unidades',
        ],

        // =======================================================
        // --- ADICIONADO: Guard do Paciente para API (Sanctum) ---
        // =======================================================
        'paciente' => [
            'driver' => 'sanctum',
            'provider' => 'pacientes',
        ],

    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'pacientes' => [ // <-- Isto já estava correto!
            'driver' => 'eloquent',
            'model' => App\Models\Paciente::class,
        ],

        'enfermeiros' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],
        'recepcionistas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Recepcionista::class,
        ],

        'unidades' => [
            'driver' => 'eloquent',
            'model' => App\Models\Unidade::class,
        ],
    ],

    'passwords' => [
        'unidades' => [
            'provider' => 'unidades',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];