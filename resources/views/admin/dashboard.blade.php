@extends('admin.templates.admTemplate')

@section('title', 'Dashboard - Painel Administrativo')

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
      ['title' => 'A cada 10 usuários:', 'content' => "7 são mulheres<br>3 são homens<br>8 são idosos"],
    ];
@endphp

<div class="overview-container">
  {{-- Header --}}
  <div class="overview-header">
    <h1><i class="bi bi-activity"></i>OVERVIEW</h1>
  </div>

  {{-- Métricas --}}
  <div class="metrics">
    @foreach($metrics as $metric)
      <div class="metric-card">
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
      <div class="chart-container">
        <canvas id="graficoEspecialidades"></canvas>
      </div>

      {{-- Gráfico de Linha (Crescimento) --}}
      <div class="chart-container">
        
        <canvas id="graficoLinha"></canvas>
      </div>
    </div>

   {{-- Info cards --}}
    <div class="info-cards-container">
        
      {{-- Card de Gênero (gráfico Donut) --}}
      <div class="info-card">
        <h3>Índice de Gênero</h3>
        <div class="donut-chart">
          <canvas id="graficoDonutGenero"></canvas>
        </div>
      </div>

      {{-- Card 75% IDOSOS --}}
      <div class="info-card">
          <div class="container-card-idosos">
              <img class="img-pessoas" src="{{ asset('img/icon-pessoas.png') }}" alt="Ícone de idoso">
              <h3 class="h3-pessoas">75% IDOSOS</h3>
          </div>
          <img class="img-logo-prontuario" src="{{ asset('img/adm-logo1.png') }}">
      </div>

      {{-- Card UBS cadastradas com mapa --}}
      <div class="info-card">
        <h3>UBS Cadastradas</h3>
        <div class="container-ubs">
          <strong>10</strong>
          <img class="mapa" src="{{ asset('img/icon-mapa.png') }}" alt="Mapa do Brasil">
        </div>
      </div>
      
      <div class="info-card-user">
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
                    max: 1000,
                    ticks: {
                        stepSize: 100
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
                size: 20, // Aumenta o tamanho da fonte para 20px
                weight: 'bold' // Deixa o texto em negrito
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
        scales: { // Adiciona a configuração de escala aqui
            y: {
                beginAtZero: true,
                max: 1000, // Eixo Y vai até 1000
                ticks: {
                    stepSize: 100 // Intervalos de 100
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Crescimento de Cadastro de Pacientes nos Últimos Meses',
                color: '#0618b9',
                font: {
                size: 20, // Aumenta o tamanho da fonte para 20px
                weight: 'bold' // Deixa o texto em negrito
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
                  position: 'top', // Coloca a legenda na parte inferior
                  align: 'center', // Alinha os itens da legenda ao centro
                  labels: {
                      usePointStyle: true, // Usa o estilo de ponto para a legenda
                  }
                  
              }
              
          }
      }
  });
</script>
@endsection