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

        'medico' => [
            'driver' => 'session',
            'provider' => 'medicos',
        ],

        'enfermeiro' => [
            'driver' => 'session',
            'provider' => 'enfermeiros',
        ],

        'recepcionista' => [
            'driver' => 'session',
            'provider' => 'recepcionistas',
        ],

        // 🔹 NOVO GUARD PARA UNIDADE
        'unidade' => [
            'driver' => 'session',
            'provider' => 'unidades',
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

        'medicos' => [
            'driver' => 'eloquent',
            'model' => App\Models\Medico::class,
        ],

        'enfermeiros' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],
        'recepcionistas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Recepcionista::class,
        ],

        // 🔹 NOVO PROVIDER PARA UNIDADE
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
