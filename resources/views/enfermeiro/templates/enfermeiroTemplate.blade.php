<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel Administrativo')</title>

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/enfermeiro/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/enfermeiro/dashboardEnfermeiro.css') }}">

  

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <a href="{{ route('admin.perfil') }}">
            <img src="{{ asset('img/enfermeiro-logo2.png') }}" alt="Logo Prontuário+" class="logo">
        </a>

        <nav>
            <a href="{{ route('enfermeiro.dashboard') }}" title="Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <a href="{{ route('enfermeiro.pacientes') }}" title="Pacientes"><i class="bi bi-people-fill"></i></a>
            <a href="#" title="enfermeiro.prontuario"><i class="bi bi-file-medical-fill"></i></a>
            <a href="{{ route('enfermeiro.ajuda') }}" title="Ajuda"><i class="bi bi-question-circle-fill"></i></a>
            <a href="{{ route('enfermeiro.seguranca') }}" title="Segurança"><i class="bi bi-shield-lock-fill"></i></a>
            <a href="{{ route('enfermeiro.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               title="Sair">
                <i class="bi bi-power"></i>
            </a>
            <form id="logout-form" action="{{ route('enfermeiro.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>
    

    {{-- Header --}}
    <header class="header">
        <a href="{{ route('enfermeiro.perfil') }}" class="user-info" style="text-decoration: none; color: inherit;">
            @if(isset($enfermeiro) && $enfermeiro->foto)
                <img src="{{ asset('storage/fotos/' . $enfermeiroo->foto) }}" alt="Foto do Enfermeiro">
            @else
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
            @endif
            <span>{{ $enfermeiro->nomeEnfermeiro ?? 'Enfermeiro' }}</span>
        </a>
    </header>

    <main class="content">
        @yield('content')
    </main>
</body>
</html>
