<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/admin/dashboardAdm.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/template.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
  @php $admin = auth()->guard('admin')->user(); @endphp

  <div class="sidebar">
    <a href="{{ route('admin.perfil') }}">
      <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo Prontuário+" class="logo">
    </a>
    <nav>
      <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
      <a href="{{ route('admin.pacientes') }}"><i class="bi bi-people-fill"></i></a>
      <a href="{{ route('admin.manutencaoMedicos') }}"><i class="bi bi-plus-circle-fill"></i></a>
      <a href="{{ route('admin.ajuda') }}"><i class="bi bi-question-circle-fill"></i></a>
      <a href="{{ route('admin.seguranca') }}"><i class="bi bi-shield-lock-fill"></i></a>
      <a href="{{ route('admin.logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-power"></i>
      </a>
      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </nav>
  </div>

  <div class="main-dashboard-wrapper">
    <header class="header">
      <a href="{{ route('admin.perfil') }}" class="user-info" style="text-decoration: none; color: inherit;">
        @if($admin && $admin->foto)
        <img src="{{ asset('storage/fotos/' . $admin->foto) }}" alt="Foto do Admin">
        @else
        <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Foto padrão">
        @endif
        <span>{{ $admin->nomeAdmin ?? 'Administrador' }}</span>
      </a>
    </header>

    <main class="main-dashboard">
      <div class="overview-container">
        <div class="overview-header">
          <h1><i class="bi bi-activity"></i> OVERVIEW</h1>
        </div>

        <div class="metrics">
          <div class="metric-card">
            Médicos cadastrados<br>
            <strong>{{ $adminCount ?? 0 }}</strong>
          </div>
          <div class="metric-card">
            Pacientes cadastrados<br>
            <strong>{{ $patientsCount ?? 0 }}</strong>
          </div>
          <div class="metric-card">
            Exames pendentes<br>
            <strong>{{ $pendingExamsCount ?? 0 }}</strong>
          </div>
        </div>

        <div class="content-wrapper">
          <div id="bar-chart-container" class="chart-container">
            <canvas id="graficoBarras"></canvas>
          </div>
          <div id="line-chart-container" class="chart-container">
            <canvas id="graficoLinha"></canvas>
          </div>

          <div class="info-cards-container">
            <div class="info-card">
              <h3>Índice de gênero</h3>
              <div style="width: 120px; height: 120px;">
                <canvas id="graficoDonutGenero"></canvas>
              </div>
            </div>
            <div class="info-card">
              <h3>75% IDOSOS</h3>
            </div>
            <div class="info-card">
              <h3>UBS cadastradas</h3>
              <strong>{{ $ubsCount ?? 0 }}</strong>
            </div>
            <div class="info-card">
              <h3>A cada 10 usuários:</h3>
              <p>7 são mulheres<br>3 são homens<br>8 são idosos</p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

</body>

</html>