@extends('admin.templates.admTemplate')

@section('title', 'Dashboard - Painel Administrativo')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/dashboardAdm.css') }}">

@php
    $admin = auth()->guard('admin')->user();
    $metrics = [
      ['title' => 'Médicos cadastrados', 'value' => $adminCount ?? 0],
      ['title' => 'Pacientes cadastrados', 'value' => $patientsCount ?? 0],
      ['title' => 'Enfermeiros cadastrados', 'value' => $nursesCount ?? 0], // ← NOVO CARD
    ];
    $infoCards = [
      ['title' => '75% IDOSOS', 'content' => null],
      ['title' => 'A cada 10 usuários:', 'content' => "7 são mulheres<br>3 são homens<br>8 são idosos"],
    ];
@endphp

<div class="overview-container">
  {{-- Header --}}
  <h1 class="dashboard-title fade-in" style="animation-delay: 0.2s;">Dashboard Administrador</h1>

{{-- 1. Banner de Boas-vindas (Full Width) --}}
<div class="welcome-banner zoom-in-banner" style="animation-delay: 0.4s;">
    <div class="banner-left">
        {{-- Logo Prontuário+ --}}
        <div class="banner-logo-container">
            <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
        </div>
    </div>
    <div class="banner-center">
        <h2>Bem-vindo(a) <span class="name"> {{ $nome ?? 'Esther' }}</span></h2>
        <p>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
    </div>
    <div class="banner-right">
        {{-- Ilustração de médicos --}}
        <img src="{{ asset('img/ministerio.png') }}" alt="Ilustração de Médicos" class="funcionarios-image">
    </div>
</div>

  {{-- Métricas --}}
  <div class="metrics">
    @foreach($metrics as $index => $metric)
      <div class="metric-card slide-up" style="animation-delay: {{ 0.6 + ($index * 0.2) }}s;">
        <div class="container-img">
            <img src="{{ asset('img/usuario-de-perfil.png') }}">
          <span>{{ $metric['title'] }}</span>
        </div>
        <strong>{{ $metric['value'] }}</strong>
      </div>
    @endforeach
  </div>

  {{-- Conteúdo com gráficos e info cards --}}
  <div class="content-wrapper">

    {{-- Gráficos --}}
    <div class="charts">

      {{-- Gráfico de Barras (Especialidades) --}}
      <div class="chart-container slide-up" style="animation-delay: 1.2s;">
        <canvas id="graficoEspecialidades"></canvas>
      </div>

      {{-- Gráfico de Linha (Crescimento) --}}
      <div class="chart-container slide-up" style="animation-delay: 1.4s;">
        
        <canvas id="graficoLinha"></canvas>
      </div>
    </div>

   {{-- Info cards --}}
    <div class="info-cards-container">
        
      {{-- Card de Gênero (gráfico Donut) --}}
      <div class="info-card slide-up" style="animation-delay: 1.6s;">
        <h3>Índice de Gênero</h3>
        <div class="donut-chart">
          <canvas id="graficoDonutGenero"></canvas>
        </div>
      </div>

      {{-- Card 75% IDOSOS --}}
      <div class="info-card slide-up" style="animation-delay: 1.8s;">
          <div class="container-card-idosos">
            <img class="img-pessoas" src="{{ asset('img/icon-pessoas.png') }}" alt="Ícone de idoso">
            <h3 class="h3-pessoas">75% IDOSOS</h3>
          </div>
          <img class="img-logo-prontuario" src="{{ asset('img/adm-logo1.png') }}">
      </div>

      {{-- Card UBS cadastradas com mapa --}}
      <div class="info-card slide-up" style="animation-delay: 2.0s;">
        <h3>UBS Cadastradas</h3>
        <div class="container-ubs">
          <strong>10</strong>
          <img class="mapa" src="{{ asset('img/icon-mapa.png') }}" alt="Mapa do Brasil">
        </div>
      </div>
      
      <div class="info-card-user slide-up" style="animation-delay: 2.2s;">
          <img class="dez-pessoas" src="{{ asset('img/icon-dezpessoas.png') }}" alt="logo do SUS">
        <h3>A Cada 10 Usuários do Aplicativo...</h3>
        <ul>
        @php
          // Divide o conteúdo em uma array de strings, usando a quebra de linha <br> como separador
          $contentLines = explode('<br>', $infoCards[1]['content']);
        @endphp
        @foreach($contentLines as $line)
          <li>{{ trim($line) }}</li>
        @endforeach
        </ul>
        <img src="{{ asset('img/logo-sus.png') }}" alt="logo do SUS">
      </div>

    </div>
  </div>
</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ====== GRÁFICO DE BARRAS (ESPECIALIDADES) ======
    const ctxEspecialidade = document.getElementById('graficoEspecialidades');
    new Chart(ctxEspecialidade, {
        type: 'bar',
        data: {
            labels: {!! json_encode($medicosPorEspecialidade->pluck('especialidadeMedico')) !!},
            datasets: [{
                label: 'Médicos',
                data: {!! json_encode($medicosPorEspecialidade->pluck('total')) !!},
                backgroundColor: '#0a27d6',
                borderColor: '#0a27d6',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    },
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#000',
                },

                title: {
                display: true,
                text: 'Quantidade de Médicos por Especialidade',
                color: '#0618b9',
                font: {
                size: 20, 
                weight: 'bold' 
                }
                }
            }
        }
    });

    // ====== GRÁFICO DE LINHA (CRESCIMENTO) ======
    const ctxLinha = document.getElementById('graficoLinha').getContext('2d');
const dadosLinha = @json($dadosLinha);

new Chart(ctxLinha, {
    type: 'line',
    data: {
        labels: dadosLinha.meses,
        datasets: [
            {
                label: 'Pacientes',
                data: dadosLinha.pacientes,
                borderColor: '#0618b9',
                backgroundColor: '#E9ECFF',
                fill: true,
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        scales: { 
            y: {
                beginAtZero: true,
                max: 100, 
                ticks: {
                    stepSize: 10 
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Crescimento de Cadastro de Pacientes nos Últimos Meses',
                color: '#0618b9',
                font: {
                size: 20, 
                weight: 'bold' 
                }
            }
        }
    }
});

    // ====== GRÁFICO DONUT (GÊNERO) ======
  const ctxGenero = document.getElementById('graficoDonutGenero');
  new Chart(ctxGenero, {
      type: 'pie',
      data: {
          labels: ['Homens', 'Mulheres'],
          datasets: [{
              data: [
                  {{ $dadosGenero['Homens'] }},
                  {{ $dadosGenero['Mulheres'] }},
              ],
              backgroundColor: ['#0000ff', '#ff0066']
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'top', 
                  align: 'center', 
                  labels: {
                      usePointStyle: true, 
                  }
                  
              }
              
          }
      }
  });
</script>
@endsection