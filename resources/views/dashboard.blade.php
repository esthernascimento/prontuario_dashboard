<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Prontuário+</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="sidebar">
  <img src="{{ asset('img/logo-branco.png') }}" alt="Logo Prontuário+" class="logo">
  <nav>
    <a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
    <a href="{{ route('pacientes') }}"><i class="bi bi-people-fill"></i></a>
    <a href="{{ route('ajuda') }}"><i class="bi bi-question-circle-fill"></i></a>
    <a href="{{ route('seguranca') }}"><i class="bi bi-shield-lock-fill"></i></a>
    <a href="{{ route('logout') }}"><i class="bi bi-power"></i></a>
  </nav>
</div>

<div class="main-dashboard-wrapper">
  <header class="header">
    <div class="user-info">
      <img src="{{ asset('img/julia.png') }}" alt="Foto da Dra. Júlia">
      <span>Dra. Júlia Marcelli</span>
    </div>
  </header>

  <main class="main-dashboard">
    <h1>OVERVIEW</h1>

    <div class="metrics">
      <div class="metric-card">Médicos cadastrados<br><strong>{{ $adminsCount ?? 0 }}</strong></div>
      <div class="metric-card">Pacientes cadastrados<br><strong>{{ $patientsCount ?? 0 }}</strong></div>
      <div class="metric-card">Exames pendentes<br><strong>{{ $pendingExamsCount ?? 0 }}</strong></div>
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
  </main>
</div>

</body>
</html>