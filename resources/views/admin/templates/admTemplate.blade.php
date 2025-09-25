<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel Administrativo')</title>

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/admin/template.css') }}">

    {{-- Bootstrap Icons (caso não tenha no CSS principal) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <a href="{{ route('admin.perfil') }}">
            <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo Prontuário+" class="logo">
        </a>

        <nav>
            <a href="{{ route('admin.dashboard') }}" title="Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <a href="{{ route('admin.pacientes') }}" title="Pacientes"><i class="bi bi-people-fill"></i></a>
            <a href="{{ route('admin.manutencaoMedicos') }}" title="Médicos"><i class="bi bi-plus-circle-fill"></i></a>
            <a href="{{ route('admin.ajuda') }}" title="Ajuda"><i class="bi bi-question-circle-fill"></i></a>
            <a href="{{ route('admin.seguranca') }}" title="Segurança"><i class="bi bi-shield-lock-fill"></i></a>

            {{-- Logout --}}
            <a href="{{ route('admin.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Sair">
                <i class="bi bi-power"></i>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    {{-- Header --}}
    <header class="header">
        <a href="{{ route('admin.perfil') }}" class="user-info" style="text-decoration: none; color: inherit;">
            @if(isset($admin) && $admin->foto)
                <img src="{{ asset('storage/fotos/' . $admin->foto) }}" alt="Foto do Admin">
            @else
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
            @endif
            <span>{{ $admin->nomeAdmin ?? 'Administrador' }}</span>
        </a>
    </header>

    {{-- Conteúdo Dinâmico --}}
    <main class="main-dashboard">
        @yield('content')
    </main>
</body>
</html>
