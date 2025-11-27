@extends('medico.templates.medicoTemplate')

@section('title', 'Dashboard - Prontuário+')

@section('content')

<link rel="stylesheet" href="{{ asset('css/medico/dashboardMedico.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="main-dashboard">
    <div class="overview-container">

        <div class="dashboard-header fade-in" style="animation-delay: 0s;">
            <div class="header-content">
                <div class="header-left">
                    <h1>Dashboard do Médico</h1>
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

        <div class="welcome-banner zoom-in-banner" style="animation-delay: 0.1s;">
            <div class="banner-decoration"></div>
            <div class="banner-content">
                <div class="banner-left">
                    <div class="banner-logo-container">
                        <img src="{{ asset('img/medico-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
                    </div>
                </div>
                <div class="banner-center">

                    <h2>Bem-vindo(a), <span class="doctor-name">Dr(a). {{ $nome ?? 'Médico(a)' }}</span></h2>
                    <p><i class="bi bi-heart-pulse"></i>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
                </div>
                <div class="banner-right">
                    <img src="{{ asset('img/funcionarios.png') }}" alt="Ilustração de Médicos" class="funcionarios-image">
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="metrics">
                <div class="metric-card slide-up" style="animation-delay: 0.2s;">
                    <div class="metric-icon">
                        <img src="{{ asset('img/icon-pessoa.png') }}" alt="Ícone Pacientes" class="icon-metric-img">
                    </div>
                    <div class="metric-content">
                        <span class="metric-label">Pacientes ativos</span>

                        <strong class="metric-value">{{ $patientsCount ?? 0 }}</strong>
                    </div>
                </div>

                <div class="metric-card slide-up" style="animation-delay: 0.3s;">
                    <div class="metric-icon">
                        <img src="{{ asset('img/icon-prontuario.png') }}" alt="Ícone Prontuários" class="icon-metric-img">
                    </div>
                    <div class="metric-content">
                        <span class="metric-label">Total de Prontuários</span>

                        <strong class="metric-value">{{ $prontuariosCount ?? 0 }}</strong>
                    </div>
                </div>
            </div>

            <div class="sus-logo-container slide-up" style="animation-delay: 0.4s;">
                <i class="bi bi-hospital sus-icon"></i>
                <span class="sus-text">SUS</span>
                <p>Sistema Único de Saúde</p>
            </div>
        </div>

        <div class="charts-section">
            <div class="section-header fade-in" style="animation-delay: 0.5s;">
                <h2><i class="bi bi-bar-chart-fill"></i> Estatísticas e Análises</h2>
                <p>Acompanhe seus atendimentos em tempo real</p>
            </div>

            <div class="charts-grid">
        
                <div class="chart-card slide-up" style="animation-delay: 0.6s;">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="bi bi-calendar-week"></i>
                            <h3>Atendimentos por Mês</h3>
                        </div>
                        <button class="chart-options" title="Opções">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="chart-body">
                        <canvas id="graficoBarras"></canvas>
                    </div>
                    <div class="chart-footer">
                        <span class="chart-info">
                            <i class="bi bi-info-circle"></i>
                            Distribuição mensal de consultas (apenas suas)
                        </span>
                    </div>
                </div>

                <div class="chart-card slide-up" style="animation-delay: 0.7s;">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="bi bi-graph-up-arrow"></i>
                            <h3>Evolução de Atendimentos</h3>
                        </div>
                        <button class="chart-options" title="Opções">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="chart-body">
                        <canvas id="graficoLinha"></canvas>
                    </div>
                    <div class="chart-footer">
                        <span class="chart-info">
                            <i class="bi bi-info-circle"></i>
                            Crescimento ao longo do último ano (apenas seus)
                        </span>
                    </div>
                </div>

                <div class="chart-card chart-card-small slide-up" style="animation-delay: 0.8s;">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="bi bi-pie-chart-fill"></i>
                            <h3>Tipos de Atendimento</h3>
                        </div>
                        <button class="chart-options" title="Opções">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                    <div class="chart-body chart-body-donut">
                        <canvas id="graficoDonut"></canvas>
                    </div>
                    <div class="chart-footer">
                        <div class="legend-row">
                            @if(isset($tiposAtendimento) && count($tiposAtendimento) > 0)
                                @foreach($tiposAtendimento as $index => $item)
                                    @php
                                        // Define as cores para consistência
                                        $color = $index == 0 ? '#8c1007' : ($index == 1 ? '#a33e38' : '#333');
                                    @endphp
                                    <div class="legend-item">
                                        <span class="legend-color" style="background: {{ $color }};"></span>
                                        <span>{{ $item->label ?? 'N/D' }} ({{ $item->total ?? 0 }})</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="legend-item">
                                    <span class="legend-color" style="background: #ccc;"></span>
                                    <span>Nenhum dado de atendimento.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    Chart.defaults.font.family = 'Montserrat, sans-serif';
    Chart.defaults.color = '#6B7280';
    const primaryColor = '#8c1007';
    const secondaryColor = '#a33e38';
    
    const atendimentosData = @json($atendimentosPorMes ?? []);
    const evolucaoData = @json($evolucaoAtendimentos ?? []);
    const tiposAtendimentoData = @json($tiposAtendimento ?? []); 

    if (document.getElementById('graficoBarras')) {
        const labelsBarras = Object.keys(atendimentosData);
        const dataBarras = Object.values(atendimentosData);

        const ctxBarras = document.getElementById('graficoBarras').getContext('2d');
        new Chart(ctxBarras, {
            type: 'bar',
            data: {
                labels: labelsBarras,
                datasets: [{
                    label: 'Atendimentos',
                    data: dataBarras,
                    backgroundColor: 'rgba(140, 16, 7, 0.8)',
                    borderColor: primaryColor,
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: primaryColor,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: primaryColor,
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' atendimento(s)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 10,
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    if (document.getElementById('graficoLinha')) {
        const labelsLinha = evolucaoData.map(item => item.label);
        const dataLinha = evolucaoData.map(item => item.total);

        const ctxLinha = document.getElementById('graficoLinha').getContext('2d');
        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: labelsLinha,
                datasets: [{
                    label: 'Total de Atendimentos',
                    data: dataLinha,
                    borderColor: primaryColor,
                    backgroundColor: 'rgba(140, 16, 7, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: primaryColor,
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: primaryColor,
                    pointHoverBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' atendimentos';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    if (document.getElementById('graficoDonut')) {
        const labelsDonut = tiposAtendimentoData.map(item => item.label);
        const dataDonut = tiposAtendimentoData.map(item => item.total);
        
        const ctxDonut = document.getElementById('graficoDonut').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: labelsDonut,
                datasets: [{
                    data: dataDonut,
                    backgroundColor: [primaryColor, secondaryColor, '#d0655d', '#c24545'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + percentage + '% (' + context.parsed + ')';
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.slide-up, .fade-in, .zoom-in-banner');
        
        animatedElements.forEach(element => {
            const delayString = element.style.animationDelay || '0s';
            const delay = parseFloat(delayString) * 1000;
            
            setTimeout(() => {
                
                element.style.animationName = element.classList.contains('slide-up') ? 'fadeInSlideUp' : 
                                             element.classList.contains('fade-in') ? 'fadeIn' : 
                                             'zoomIn'; 
                
                element.style.animationDuration = '0.8s'; 
                element.style.animationTimingFunction = 'ease-out'; 
                element.style.animationFillMode = 'forwards';
                element.style.animationDelay = '0s';
            }, delay);
        });
    });
</script>

@endsection