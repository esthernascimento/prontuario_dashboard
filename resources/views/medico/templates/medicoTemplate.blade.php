<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Painel Médico')</title>

  <link rel="stylesheet" href="{{ asset('css/medico/template.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>

  {{-- Sidebar --}}
  <div class="sidebar">
    <a href="{{ route('medico.dashboard') }}">
      <img src="{{ asset('img/medico-logo.png') }}" alt="Logo Prontuário+" class="logo"> </a>

    <nav>
      <a href="{{ route('medico.dashboard') }}" title="Dashboard"><i class="bi bi-house-door-fill"></i></a>
      <a href="{{ route('medico.prontuario') }}" title="Prontuário"><i class="bi bi-journal-medical"></i></a>

      <a href="{{ route('medico.ajuda') }}" title="Ajuda"><i class="bi bi-question-circle-fill"></i></a>

      <a href="{{ route('medico.perfil') }}" title="perfil"><i class="bi bi-shield-lock-fill"></i></a>

      <a href="{{ route('medico.logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        title="Sair">
        <i class="bi bi-power"></i>
      </a>
      <form id="logout-form" action="{{ route('medico.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </nav>
  </div>

  {{-- Header --}}
  <header class="header">
    <a href="{{ route('medico.perfil') }}" class="user-info" style="text-decoration: none; color: inherit;">
      @php $medico = auth()->user(); @endphp
      @if($medico && $medico->foto)
      <img src="{{ asset('storage/fotos/' . $medico->foto) }}" alt="Foto do Médico">
      @else
      <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
      @endif
      <span>{{ $medico->nomeMedico ?? 'Médico' }}</span>
    </a>
  </header>

  {{-- Conteúdo --}}
  <main class="content">
    @yield('content')
  </main>

</body>

</html>