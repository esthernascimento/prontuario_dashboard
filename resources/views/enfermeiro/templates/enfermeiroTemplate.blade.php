<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- A title aqui foi ajustada para o padrão do enfermeiro --}}
    <title>@yield('title', 'Painel do Enfermeiro')</title> 

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/enfermeiro/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/enfermeiro/dashboardEnfermeiro.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        {{-- CORRIGIDO: O link do logo deve ir para o perfil do enfermeiro, não do admin --}}
        <a href="{{ route('enfermeiro.perfil') }}">
            <img src="{{ asset('img/enfermeiro-logo2.png') }}" alt="Logo Prontuário+" class="logo">
        </a>

        <nav>
            <a href="{{ route('enfermeiro.dashboard') }}" title="Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <a href="{{ route('enfermeiro.prontuario') }}" title="Prontuário"><i class="bi bi-file-medical-fill"></i></a>
            <a href="{{ route('enfermeiro.ajuda') }}" title="Ajuda"><i class="bi bi-question-circle-fill"></i></a>
            <a href="{{ route('enfermeiro.perfil') }}" title="perfil"><i class="bi bi-shield-lock-fill"></i></a>
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
             {{-- CORRIGIDO: Verifica se $enfermeiro existe e corrige a variável $enfermeiroo para $enfermeiro --}}
            @if(isset($enfermeiro) && $enfermeiro->foto)
                <img src="{{ asset('storage/fotos/' . $enfermeiro->foto) }}" alt="Foto do Enfermeiro">
            @else
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
            @endif
             {{-- CORRIGIDO: Acessa a propriedade 'nomeEnfermeiro' do objeto $enfermeiro --}}
            <span>{{ $enfermeiro->nomeEnfermeiro ?? 'Enfermeiro' }}</span> 
        </a>
    </header>

    <main class="content">
        @yield('content')
    </main>
</body>
</html>
