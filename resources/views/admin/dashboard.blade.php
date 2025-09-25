@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/dashboardAdm.css') }}">

@php 
    $admin = auth()->guard('admin')->user();

  // Métricas principais
  $metrics = [
    ['title' => 'Médicos cadastrados', 'value' => $adminCount ?? 0],
    ['title' => 'Pacientes cadastrados', 'value' => $patientsCount ?? 0],
    ['title' => 'Admins cadastrados', 'value' => $pendingExamsCount ?? 0],  
  ];

  // Cards informativos
  $infoCards = [
    ['title' => '75% IDOSOS', 'content' => null],
    ['title' => 'UBS cadastradas', 'content' => $ubsCount ?? 0],
    ['title' => 'A cada 10 usuários:', 'content' => "7 são mulheres<br>3 são homens<br>8 são idosos"],
  ];
@endphp

@section('title', 'Dashboard - Painel Administrativo')

@section('content')
  <div class="overview-container">

    {{-- Header --}}
    <div class="overview-header">
      <h1><i class="bi bi-activity"></i> OVERVIEW</h1>
    </div>

    <div class="metrics">
      @foreach($metrics as $metric)
        <div class="metric-card">
          <span>{{ $metric['title'] }}</span>
          <strong>{{ $metric['value'] }}</strong>
        </div>
      @endforeach
    </div>

    {{-- Conteúdo com gráficos e info cards --}}
    <div class="content-wrapper">

      {{-- Gráficos --}}
      <div class="charts">
        <div class="chart-container">
          <div id="top_x_div"></div>
        </div>
        <div class="chart-container">
          <canvas id="graficoLinha"></canvas>
        </div>
      </div>

      {{-- Info cards --}}
      <div class="info-cards-container">

        {{-- Card com gráfico donut --}}
        <div class="info-card">
          <h3>Índice de gênero</h3>
          <div class="donut-chart">
            <canvas id="graficoDonutGenero"></canvas>
          </div>
        </div>

        {{-- Outros cards --}}
        @foreach($infoCards as $card)
          <div class="info-card">
            <h3>{{ $card['title'] }}</h3>
            @if($card['content'])
              <p>{!! $card['content'] !!}</p>
            @endif
          </div>
        @endforeach

      </div>
    </div>
  </div>


  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', { 'packages': ['bar'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
     
      const medicosData = @json($medicosPorEspecialidade);

      let dataArray = [['Especialidade', 'Quantidade']];

      medicosData.forEach(item => {
        dataArray.push([item.especialidadeMedico, item.total]);
      });

      var data = google.visualization.arrayToDataTable(dataArray);

      var options = {
        
        legend: { position: 'none' },
        chart: {
          title: 'Médicos Cadastrados por Especialidade',
          subtitle: 'Contagem de médicos agrupados por sua especialidade'
        },
        axes: {
          x: {
            0: { side: 'top', label: 'Especialidades' }
          }
        },
        bar: { groupWidth: "90%" }
      };

      var chart = new google.charts.Bar(document.getElementById('top_x_div'));
      chart.draw(data, google.charts.Bar.convertOptions(options));
    }
  </script>
@endsection