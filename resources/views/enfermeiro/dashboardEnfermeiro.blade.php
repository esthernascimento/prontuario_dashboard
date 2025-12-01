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
            <i class="bi bi-person-fill-gear"></i>
        </div>
        <div class="metric-content">
            <span class="metric-label">Total Pacientes Próprios</span>
            <strong class="metric-value">{{ $pacientesProprios ?? 0 }}</strong>
        </div>
    </div>

    <div class="metric-card slide-up" style="animation-delay: 1.4s;">
        <div class="metric-icon blue">
            <i class="bi bi-clipboard2-check-fill"></i>
        </div>
        <div class="metric-content">
            <span class="metric-label">Triagens Concluídas Hoje</span>
            <strong class="metric-value">{{ $atendimentosDia ?? 0 }}</strong>
        </div>
    </div>
    
</div>

    
        <div class="charts-section">
            <div class="section-header slide-up" style="animation-delay: 1.8s;">
                <h2><i class="bi bi-bar-chart-fill"></i> Estatísticas e Análises</h2>
                <p>Monitore seus pacientes e atendimentos em tempo real</p>
            </div>
            <div class="content-wrapper"> 
                <div class="charts"> 
                    
                    <div class="chart-container slide-up" style="animation-delay: 2.0s;">
                        <canvas id="graficoPacientesMes"></canvas>
                    </div>

                   {{-- GRÁFICO DE CLASSIFICAÇÕES DE RISCO --}}
                    <div class="chart-container info-card slide-up" style="animation-delay: 1.8s;">
                        <canvas id="graficoClassificacoesRisco"></canvas>
                    </div>
                    
                    {{-- GRÁFICO DE TIPOS DE ALERGIA (BARRAS) --}}
                    <div class="chart-container slide-up" style="animation-delay: 2.4s;">
                        <canvas id="graficoVazio"></canvas>
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
    const magentaSecondary = '#ff0066'; // Cor de contraste para o gráfico de gênero

    Chart.defaults.font.family = 'Montserrat, sans-serif';
    Chart.defaults.color = '#4b5563';

    // Gráfico de Pacientes por Mês (Linhas) - AGORA COM DADOS REAIS
    const ctxPacientes = document.getElementById('graficoPacientesMes').getContext('2d');
    
    // Puxando dados dinâmicos do Laravel
    const triagensLabels = @json($dadosTriagensMes['labels']) || ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
    const triagensData = @json($dadosTriagensMes['data']) || [12, 19, 8, 15, 10, 13];

    new Chart(ctxPacientes, {
        type: 'line',
        data: {
            labels: triagensLabels, // RÓTULOS REAIS
            datasets: [{
                label: 'Triagens Realizadas',
                data: triagensData, // DADOS REAIS
                borderColor: greenPrimary, 
                backgroundColor: 'rgba(46, 125, 50, 0.1)',
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
                    text: 'Evolução de Triagens Realizadas (Últimos 6 Meses)', // TÍTULO ATUALIZADO
                    color: greenPrimary,
                    font: { size: 16, weight: 'bold' }
                }
            }
        }
    });

   // Gráfico de Classificações de Risco (Linhas)
const ctxClassificacoes = document.getElementById('graficoClassificacoesRisco').getContext('2d');
const dadosClassificacoes = @json($dadosClassificacoesRisco);

new Chart(ctxClassificacoes, {
    type: 'line',
    data: {
        labels: dadosClassificacoes.labels,
        datasets: dadosClassificacoes.datasets
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: { 
                beginAtZero: true,
                ticks: { 
                    stepSize: 1,
                    font: { size: 11 }
                },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { 
                grid: { display: false },
                ticks: { font: { size: 11 } }
            }
        },
        plugins: {
            legend: { 
                display: true,
                position: 'top',
                labels: { 
                    usePointStyle: true,
                    padding: 12,
                    font: { size: 11, weight: '500' },
                    boxWidth: 8,
                    boxHeight: 8
                }
            },
            title: {
                display: true,
                text: 'Classificações de Risco (Últimos 6 Meses)',
                color: greenPrimary,
                font: { size: 16, weight: 'bold' },
                padding: { bottom: 20 }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 },
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y + ' registro(s)';
                    }
                }
            }
        }
    }
});

    // Gráfico de Tipos de Alergia (Barras Simples)
const ctxTiposAlergia = document.getElementById('graficoVazio').getContext('2d');
const dadosTiposAlergia = @json($dadosTiposAlergia);

new Chart(ctxTiposAlergia, {
    type: 'bar',
    data: {
        labels: dadosTiposAlergia.labels,
        datasets: [{
            label: 'Quantidade de Registros',
            data: dadosTiposAlergia.data,
            borderColor: greenPrimary, 
            backgroundColor: 'rgba(46, 125, 50, 0.1)',
            borderWidth: 2,
            borderRadius: 8,
            barThickness: 40,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        indexAxis: 'x', // Barras verticais
        scales: {
            y: { 
                beginAtZero: true,
                ticks: { 
                    stepSize: 1,
                    font: { size: 11 }
                },
                grid: { 
                    color: 'rgba(0,0,0,0.05)',
                    drawBorder: false
                },
                border: {
                    display: false
                }
            },
            x: { 
                grid: { 
                    display: false,
                    drawBorder: false
                },
                ticks: { 
                    font: { size: 11 },
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 0
                },
                border: {
                    display: false
                }
            }
        },
        plugins: {
            legend: { 
                display: false // Não precisa de legenda para barras simples
            },
            title: {
                display: true,
                text: 'Tipos de Alergia Mais Registrados',
                color: greenPrimary,
                font: { size: 16, weight: 'bold' },
                padding: { bottom: 15, top: 10 }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 },
                callbacks: {
                    label: function(context) {
                        return ' Total: ' + context.parsed.y + ' registro(s)';
                    }
                }
            }
        }
    }
});
</script>

@endsection