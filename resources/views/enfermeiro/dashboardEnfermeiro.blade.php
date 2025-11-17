@extends('enfermeiro.templates.enfermeiroTemplate')

@section('title', 'Dashboard - Prontuário+')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/dashboardEnfermeiro.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-dashboard">
    <div class="overview-container">

        <div class="dashboard-header fade-in">
            <div class="header-content">
                <div class="header-left">
                    <h1>Dashboard do Enfermeiro</h1>
                    <p class="header-subtitle">Visão geral e estatísticas em tempo real</p>
                </div>
                <div class="header-right">
                    <div class="date-badge">
                        <i class="bi bi-calendar3"></i>
                        <span>{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="welcome-banner zoom-in-banner" style="animation-delay: 0.4s;">
            <div class="banner-decoration"></div>
            <div class="banner-content">
                <div class="banner-left">
                    <div class="banner-logo-container">
                        <img src="{{ asset('img/enfermeiro-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
                    </div>
                </div>
                <div class="banner-center">
               
                    <h2>Bem-vindo(a), <span class="name">{{ $enfermeiro->nomeEnfermeiro ?? 'Enfermeiro(a)' }}</span></h2>
                    <p><i class="bi bi-heart-pulse"></i>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
                </div>
                <div class="banner-right">
                    <img src="{{ asset('img/enfermeiros.png') }}" alt="Ilustração de Enfermeiros" class="funcionarios-image">
                </div>
            </div>
        </div>

        <div class="metrics">
            <div class="metric-card slide-up" style="animation-delay: 0.6s;">
                <div class="metric-icon red">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Atendimentos no Dia</span>
                    <strong class="metric-value">{{ $atendimentosDia ?? 0 }}</strong>
                </div>
            </div>

            <div class="metric-card slide-up" style="animation-delay: 0.8s;">
                <div class="metric-icon purple">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Pacientes Próprios</span>
                    <strong class="metric-value">{{ $pacientesProprios ?? 0 }}</strong>
                </div>
            </div>

            <div class="metric-card slide-up" style="animation-delay: 1.0s;">
                <div class="metric-icon green">
                    <i class="bi bi-hospital-fill"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Unidade de Atuação</span>
                    <strong class="metric-value metric-text-small">{{ $unidadeAtuacao ?? 'N/A' }}</strong>
                </div>
            </div>

            <div class="metric-card slide-up" style="animation-delay: 1.2s;">
                <div class="metric-icon orange">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div class="metric-content">
                    <span class="metric-label">Agendamentos Hoje</span>
                    <strong class="metric-value">{{ $agendamentosHoje ?? 0 }}</strong>
                </div>
            </div>
        </div>


        <div class="charts-section">
            <div class="section-header slide-up" style="animation-delay: 1.4s;">
                <h2><i class="bi bi-bar-chart-fill"></i> Estatísticas e Análises</h2>
                <p>Monitore seus pacientes e atendimentos em tempo real</p>
            </div>
            <div class="content-wrapper"> 
                <div class="charts"> 
                 
                    <div class="chart-container slide-up" style="animation-delay: 1.6s;">
                        <canvas id="graficoPacientesMes"></canvas>
                    </div>

                    {{-- GRÁFICO DE DONUT ENFERMEIRO --}}
                    <div class="chart-container info-card slide-up" style="animation-delay: 1.8s;">
                        <h3>Índice de Gênero</h3>
                        <div class="donut-chart">
                            <canvas id="graficoDonutEnfermeiro"></canvas>
                        </div>
                    </div>
                    
                    {{-- GRÁFICO DE BARRAS DE SERVIÇOS --}}
                    <div class="chart-container slide-up" style="animation-delay: 2.0s;">
                        <canvas id="graficoVazio"></canvas>
                    </div>
                </div>

                <div class="info-cards-container">
                    <div class="info-card slide-up" style="animation-delay: 2.2s;">
                        <h3>Taxa de Acolhimento</h3>
                        <strong style="font-size: 3rem;">92%</strong>
                        <p>Pacientes atendidos no tempo correto</p>
                    </div>
                    <div class="info-card slide-up" style="animation-delay: 2.4s;">
                        <h3>Média de Espera</h3>
                        <strong style="font-size: 3rem;">15 min</strong>
                        <p>Tempo médio até o primeiro atendimento</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Definição das cores para o tema verde
    const greenPrimary = '#2e7d32'; // Verde Escuro Principal
    const greenLightBackground = '#E8F5E9'; // Fundo suave para linha/área

    Chart.defaults.font.family = 'Montserrat, sans-serif';
    Chart.defaults.color = '#4b5563';

    // Gráfico de Pacientes por Mês (Linhas)
    const ctxPacientes = document.getElementById('graficoPacientesMes').getContext('2d');
    new Chart(ctxPacientes, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Atendimentos',
                data: [12, 19, 8, 15, 10, 13],
                borderColor: greenPrimary, // Verde
                backgroundColor: 'rgba(46, 125, 50, 0.1)', // Fundo suave verde
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Evolução de Atendimentos nos Últimos Meses',
                    color: greenPrimary, // Verde
                    font: { size: 16, weight: 'bold' }
                }
            }
        }
    });

    // Gráfico de Donut - Gênero dos Enfermeiros 
    const ctxEnfermeiro = document.getElementById('graficoDonutEnfermeiro').getContext('2d');
    new Chart(ctxEnfermeiro, {
        type: 'doughnut',
        data: {
            labels: ['Homens', 'Mulheres'],
            datasets: [{
                data: [
                    {{ $dadosGeneroEnfermeiro['Homens'] ?? 40 }},
                    {{ $dadosGeneroEnfermeiro['Mulheres'] ?? 60 }}
                ],
                // Cores de gênero ajustadas: um verde (homens) e um rosa/magenta (mulheres) para contraste
                backgroundColor: [greenPrimary, '#ff0066'], 
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { 
                legend: { 
                    position: 'top', 
                    align: 'center',
                    labels: { usePointStyle: true }
                }
            }
        }
    });

    // Gráfico Vazio (Exemplo de Gráfico de Barras)
    const ctxVazio = document.getElementById('graficoVazio').getContext('2d');
    new Chart(ctxVazio, {
        type: 'bar',
        data: {
            labels: ['Maniçoba', 'Vacinação', 'Curativos', 'Triagem'],
            datasets: [{
                label: 'Frequência de Serviços',
                data: [10, 25, 15, 30],
                backgroundColor: greenPrimary, // Verde
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, max: 40, ticks: { stepSize: 5 } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Serviços Mais Realizados (Exemplo)',
                    color: greenPrimary, // Verde
                    font: { size: 16, weight: 'bold' }
                }
            }
        }
    });
</script>

@endsection