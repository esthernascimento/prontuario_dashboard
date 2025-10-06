<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel do Enfermeiro')</title>

    {{-- CSS do Template (Sidebar + Header) --}}
    <link rel="stylesheet" href="{{ asset('css/enfermeiro/template.css') }}">

    {{-- CSS específico de cada página --}}
    @stack('styles')

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar-enfermeiro">
        <a href="{{ route('enfermeiro.dashboard') }}" class="logo-link-enfermeiro">
            <img src="{{ asset('img/enfermeiro-logo2.png') }}" alt="Logo Prontuário+" class="logo-enfermeiro">
        </a>

        <nav class="nav-enfermeiro">
            <a href="{{ route('enfermeiro.dashboard') }}" class="nav-item-enfermeiro">
                <i class="bi bi-house-door-fill"></i>
                <span class="nav-text-enfermeiro">Dashboard</span>
            </a>
            <a href="{{ route('enfermeiro.prontuario') }}" class="nav-item-enfermeiro">
                <i class="bi bi-file-medical-fill"></i>
                <span class="nav-text-enfermeiro">Prontuário</span>
            </a>
            <a href="{{ route('enfermeiro.ajuda') }}" class="nav-item-enfermeiro">
                <i class="bi bi-question-circle-fill"></i>
                <span class="nav-text-enfermeiro">Ajuda</span>
            </a>
            <a href="{{ route('enfermeiro.perfil') }}" class="nav-item-enfermeiro">
                <i class="bi bi-shield-lock-fill"></i>
                <span class="nav-text-enfermeiro">Perfil</span>
            </a>

            {{-- Logout --}}
            <a href="{{ route('enfermeiro.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form-enfermeiro').submit();"
               class="nav-item-enfermeiro">
                <i class="bi bi-power"></i>
                <span class="nav-text-enfermeiro">Sair</span>
            </a>
            <form id="logout-form-enfermeiro" action="{{ route('enfermeiro.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    {{-- Header --}}
    <header class="header-enfermeiro">
        <a href="{{ route('enfermeiro.perfil') }}" class="user-info-enfermeiro" style="text-decoration: none; color: inherit;">
            @if(isset($enfermeiro) && $enfermeiro->foto)
                <img src="{{ asset('storage/fotos/' . $enfermeiro->foto) }}" alt="Foto do Enfermeiro">
            @else
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
            @endif
            <span>{{ $enfermeiro->nomeEnfermeiro ?? 'Enfermeiro' }}</span>
        </a>
    </header>

    {{-- Conteúdo Dinâmico --}}
    <main class="main-content-enfermeiro">
        @yield('content')
    </main>

    {{-- Scripts específicos de cada página --}}
    @stack('scripts')

    {{-- Script para sincronizar header com sidebar --}}
    <script>
        const sidebar = document.querySelector('.sidebar-enfermeiro');
        const header = document.querySelector('.header-enfermeiro');
        const mainContent = document.querySelector('.main-content-enfermeiro');

        sidebar.addEventListener('mouseenter', () => {
            header.style.left = '250px';
            mainContent.style.marginLeft = '250px';
        });

        sidebar.addEventListener('mouseleave', () => {
            header.style.left = '100px';
            mainContent.style.marginLeft = '100px';
        });
    </script>
</body>
</html>