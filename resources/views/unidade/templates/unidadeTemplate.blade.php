<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel da Unidade')</title>

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/unidade/template.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        {{-- 🔥 CORREÇÃO: O link do logo deve ir para o dashboard da unidade --}}
        <a href="{{ route('unidade.dashboard') }}" class="logo-link">
            <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo Prontuário+" class="logo">
        </a>

        <nav>
            <a href="{{ route('unidade.dashboard') }}" class="nav-item">
                <i class="bi bi-house-door-fill"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="{{ route('unidade.manutencaoMedicos') }}" class="nav-item">
                <i class="bi bi-plus-circle-fill"></i>
                <span class="nav-text">Médicos</span>
            </a>

            <a href="{{ route('unidade.manutencaoEnfermeiro') }}" class="nav-item">
                <i class="bi bi-person-fill-add"></i>
                <span class="nav-text">Enfermeiros</span>
            </a>

            <a href="{{ route('unidade.manutencaoRecepcionista') }}" class="nav-item">
                <i class="bi bi-people-fill"></i>
                <span class="nav-text">Recepcionista </span>
            </a>

            <a href="{{ route('unidade.ajuda') }}" class="nav-item">
                <i class="bi bi-question-circle-fill"></i>
                <span class="nav-text">Ajuda</span>
            </a>
            <a href="{{ route('unidade.perfil') }}" class="nav-item">
                <i class="bi bi-shield-lock-fill"></i>
                <span class="nav-text">Perfil</span>
            </a>

            {{-- Logout --}}
            <a href="{{ route('unidade.logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item">
                <i class="bi bi-power"></i>
                <span class="nav-text">Sair</span>
            </a>
            <form id="logout-form" action="{{ route('unidade.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    {{-- Header --}}
    <header class="header">
        {{-- 🔥 CORREÇÃO: Usar a variável $unidade e a rota unidade.perfil --}}
        <a href="{{ route('unidade.perfil') }}" class="user-info" style="text-decoration: none; color: inherit;">
            @if(isset($unidade) && $unidade->foto)
            <img src="{{ asset('storage/fotos/' . $unidade->foto) }}" alt="Foto da Unidade">
            @else
            <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
            @endif
            {{-- 🔥 CORREÇÃO: Ajustar o nome da variável --}}
            <span>{{ $unidade->nomeUnidade ?? 'Unidade' }}</span>
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