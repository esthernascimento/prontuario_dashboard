<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel Administrativo')</title>

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/admin/template.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="logo-link">
            <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo Prontuário+" class="logo">
        </a>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <i class="bi bi-house-door-fill"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.pacientes.index') }}" class="nav-item">
                <i class="bi bi-people-fill"></i>
                <span class="nav-text">Pacientes</span>
            </a>
            <a href="{{ route('admin.manutencaoMedicos') }}" class="nav-item">
                <i class="bi bi-plus-circle-fill"></i>
                <span class="nav-text">Médicos</span>
            </a>
            <a href="{{ route('admin.manutencaoEnfermeiro') }}" class="nav-item">
                <i class="bi bi-person-fill-add"></i>
                <span class="nav-text">Enfermeiros</span>
            </a>
            <a href="{{ route('admin.unidades.index') }}" class="nav-item">
                <i class="bi bi-hospital-fill"></i>
                <span class="nav-text">Unidade</span>
            </a>
            <a href="{{ route('admin.ajuda') }}" class="nav-item">
                <i class="bi bi-question-circle-fill"></i>
                <span class="nav-text">Ajuda</span>
            </a>
            <a href="{{ route('admin.perfil') }}" class="nav-item">
                <i class="bi bi-shield-lock-fill"></i>
                <span class="nav-text">Perfil</span>
            </a>

            {{-- Logout --}}
            <a href="{{ route('admin.logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item">
                <i class="bi bi-power"></i>
                <span class="nav-text">Sair</span>
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

    {{-- Script para sincronizar header e conteúdo com sidebar --}}
    <script>
        const sidebar = document.querySelector('.sidebar');
        const header = document.querySelector('.header');
        const mainDashboard = document.querySelector('.main-dashboard');

        sidebar.addEventListener('mouseenter', () => {
            header.style.left = '250px';
            mainDashboard.style.marginLeft = '250px';
        });

        sidebar.addEventListener('mouseleave', () => {
            header.style.left = '100px';
            mainDashboard.style.marginLeft = '100px';
        });
    </script>
</body>

</html>