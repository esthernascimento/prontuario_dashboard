@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')
<main class="content">
  <div class="page-container">
    <h1>Bem-vindo {{ $nome }}!</h1>

    <div class="metrics">
      <div class="metric-card">
        <i class="bi bi-person-fill"></i> Pacientes no sistema
        <strong>5</strong>
      </div>
      <div class="metric-card">
        <i class="bi bi-file-earmark-text-fill"></i> Prontuários disponíveis
        <strong>350</strong>
      </div>
    </div>

    <div class="content-wrapper">
      <div class="charts-left">
        <h4 style="color: #0a400c; margin-bottom: 20px;">Gráfico de Barras</h4>
        <div style="height: 350px; background-color: #f4f6f8; display: flex; flex-direction: column; justify-content: space-around; padding: 10px; border-radius: 10px;">
          <div style="width: 100%; height: 20px; background-color: #0a400c;"></div>
          <div style="width: 80%; height: 20px; background-color: #0a400c;"></div>
          <div style="width: 60%; height: 20px; background-color: #0a400c;"></div>
          <div style="width: 40%; height: 20px; background-color: #0a400c;"></div>
        </div>
        <p style="text-align: right; color: #0a400c; margin-top: 10px;">Ano de 40</p>
      </div>

      <div class="right-column">
        <div class="welcome-card">
          O SUS agradece a sua <br>colaboração para o nosso sistema!
          <img src="{{ asset('img/exames.png') }}" alt="Prontuário">
        </div>

        <div class="donut-card">
          <div class="donut-chart-placeholder"></div>
          <div class="text-info">
            Enfermeiros(a) ativos:<br>Homens e Mulheres
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
