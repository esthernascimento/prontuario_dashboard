@extends('unidade.templates.unidadeTemplate')

@section('title', 'Dashboard Unidade')

@section('content')

@php $unidade = auth()->guard('unidade')->user(); @endphp

<link rel="stylesheet" href="{{ asset('css/unidade/dashboardUnidade.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="dashboard-container">
    <!-- HEADER COM BOAS-VINDAS -->
    <div class="dashboard-header fade-in">
        <div class="header-content">
            <div class="header-left">
                <h1>Dashboard da Unidade</h1>
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

    <!-- BANNER DE BOAS-VINDAS -->
    <div class="welcome-banner zoom-in">
        <div class="banner-decoration"></div>
        <div class="banner-content">
            <div class="banner-left">
                <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
            </div>
            <div class="banner-center">
                <h2>Bem-vindo(a), <span class="highlight">{{ $nomeUnidade ?? 'Usuário' }}</span></h2>
                <p><i class="bi bi-heart-pulse"></i> O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
            </div>
            <div class="banner-right">
                <img src="{{ asset('img/ministerio.png') }}" alt="Ministério da Saúde" class="banner-gov-logo">
            </div>
        </div>
    </div>

    <!-- CARDS DE MÉTRICAS PRINCIPAIS -->
    <div class="metrics-grid">
        <div class="metric-card slide-up" style="animation-delay: 0.1s;">
            <div class="metric-icon blue">
                <i class="bi bi-person-hearts"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Médicos Cadastrados</span>
                <strong class="metric-value">{{ $medicosCount ?? 0 }}</strong>
            </div>
            <div class="metric-badge">
                <i class="bi bi-graph-up"></i>
            </div>
        </div>

        <div class="metric-card slide-up" style="animation-delay: 0.2s;">
            <div class="metric-icon green">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Enfermeiros Cadastrados</span>
                <strong class="metric-value">{{ $nursesCount ?? 0 }}</strong>
            </div>
            <div class="metric-badge">
                <i class="bi bi-graph-up"></i>
            </div>
        </div>

        <div class="metric-card slide-up" style="animation-delay: 0.3s;">
            <div class="metric-icon purple">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Recepcionistas Cadastrados</span>
                <strong class="metric-value">{{ $patientsCount ?? 0 }}</strong>
            </div>
            <div class="metric-badge">
                <i class="bi bi-graph-up"></i>
            </div>
        </div>

        <div class="metric-card slide-up" style="animation-delay: 0.4s;">
            <div class="metric-icon orange">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="metric-content">
                <span class="metric-label">Total de Profissionais</span>
                <strong class="metric-value">{{ ($medicosCount ?? 0) + ($nursesCount ?? 0) + ($patientsCount ?? 0) }}</strong>
            </div>
            <div class="metric-badge">
                <i class="bi bi-award-fill"></i>
            </div>
        </div>
    </div>

    <!-- SEÇÃO DE GRÁFICOS -->
    <div class="charts-section">
        <div class="section-header fade-in">
            <h2><i class="bi bi-bar-chart-fill"></i> Estatísticas e Análises</h2>
            <p>Acompanhe os dados da sua unidade em tempo real</p>
        </div>

        <div class="charts-grid">
            <!-- GRÁFICO 1: MÉDICOS POR ESPECIALIDADE -->
            <div class="chart-card slide-up" style="animation-delay: 0.5s;">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="bi bi-clipboard2-pulse"></i>
                        <h3>Médicos por Especialidade</h3>
                    </div>
                    <button class="chart-options" title="Opções">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
                <div class="chart-body">
                    <canvas id="graficoEspecialidades"></canvas>
                </div>
                <div class="chart-footer">
                    <span class="chart-info">
                        <i class="bi bi-info-circle"></i>
                        Distribuição por área médica
                    </span>
                </div>
            </div>

            <!-- GRÁFICO 2: CRESCIMENTO DE PACIENTES -->
            <div class="chart-card slide-up" style="animation-delay: 0.6s;">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h3>Crescimento de Pacientes</h3>
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
                        Evolução mensal de cadastros
                    </span>
                </div>
            </div>

            <!-- GRÁFICO 3: GÊNERO DOS PACIENTES -->
            <div class="chart-card chart-card-small slide-up" style="animation-delay: 0.7s;">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="bi bi-gender-ambiguous"></i>
                        <h3>Distribuição por Gênero</h3>
                    </div>
                    <button class="chart-options" title="Opções">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
                <div class="chart-body chart-body-donut">
                    <canvas id="graficoDonutGenero"></canvas>
                </div>
                <div class="chart-footer">
                    <div class="legend-row">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #3C0061;"></span>
                            <span>Homens: {{ $dadosGenero['Homens'] ?? 0 }}</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #DC2626;"></span>
                            <span>Mulheres: {{ $dadosGenero['Mulheres'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEÇÃO DE AÇÕES RÁPIDAS -->
    <div class="quick-actions-section fade-in">
        <div class="section-header">
            <h2><i class="bi bi-lightning-charge-fill"></i> Ações Rápidas</h2>
            <p>Acesso rápido às funcionalidades principais</p>
        </div>

        <div class="quick-actions-grid">
                <div class="action-icon blue">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="action-content">
                    <h4>Gerenciar Médicos</h4>
                    <p>Cadastre e gerencie médicos</p>
                </div>
                <div class="action-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>

            <a href="#" class="action-card slide-up" style="animation-delay: 1.1s;">
                <div class="action-icon orange">
                    <i class="bi bi-file-earmark-medical"></i>
                </div>
                <div class="action-content">
                    <h4>Relatórios</h4>
                    <p>Visualize relatórios gerenciais</p>
                </div>
                <div class="action-arrow">
                    <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- INFORMAÇÕES ADICIONAIS -->
    <div class="info-section">
        <div class="info-card-large slide-up" style="animation-delay: 1.2s;">
            <div class="info-icon">
                <i class="bi bi-info-circle-fill"></i>
            </div>
            <div class="info-content">
                <h3>Sistema de Gestão em Saúde</h3>
                <p>O Prontuário+ é uma plataforma completa para gerenciamento de unidades de saúde, desenvolvida para facilitar o trabalho dos profissionais e melhorar o atendimento aos pacientes.</p>
                <ul>
                    <li><i class="bi bi-check-circle-fill"></i> Gestão completa de profissionais</li>
                    <li><i class="bi bi-check-circle-fill"></i> Prontuários eletrônicos seguros</li>
                    <li><i class="bi bi-check-circle-fill"></i> Agendamento inteligente</li>
                    <li><i class="bi bi-check-circle-fill"></i> Relatórios e estatísticas em tempo real</li>
                </ul>
            </div>
        </div>

        <div class="info-card-small slide-up" style="animation-delay: 1.3s;">
            <div class="support-content">
                <i class="bi bi-headset"></i>
                <h4>Precisa de Ajuda?</h4>
                <p>Nossa equipe está pronta para atendê-lo</p>
                <button class="support-btn">
                    <i class="bi bi-chat-dots-fill"></i>
                    Falar com Suporte
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Configuração global dos gráficos
    Chart.defaults.font.family = 'Montserrat, sans-serif';
    Chart.defaults.color = '#6B7280';

    // GRÁFICO 1: Médicos por Especialidade
    const ctxEspecialidade = document.getElementById('graficoEspecialidades');
    if (ctxEspecialidade) {
        const especialidadesData = {!! json_encode($medicosPorEspecialidade) !!};
        
        // Se não houver dados, exibir dados de exemplo
        const labels = especialidadesData.length > 0 
            ? {!! json_encode($medicosPorEspecialidade->pluck('especialidadeMedico')) !!}
            : ['Cardiologia', 'Pediatria', 'Ortopedia', 'Clínico Geral', 'Ginecologia'];
        
        const data = especialidadesData.length > 0 
            ? {!! json_encode($medicosPorEspecialidade->pluck('total')) !!}
            : [12, 8, 6, 15, 10];

        new Chart(ctxEspecialidade, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Médicos',
                    data: data,
                    backgroundColor: 'rgba(60, 0, 97, 0.8)',
                    borderColor: '#3C0061',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: '#3C0061',
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
                        borderColor: '#3C0061',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' médico(s)';
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
                            stepSize: 5,
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

    // GRÁFICO 2: Crescimento de Pacientes (Linha)
    const ctxLinha = document.getElementById('graficoLinha');
    if (ctxLinha) {
        const dadosLinha = @json($dadosLinha);
        
        const meses = dadosLinha.meses.length > 0 
            ? dadosLinha.meses 
            : ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        
        const pacientes = dadosLinha.pacientes.length > 0 
            ? dadosLinha.pacientes 
            : [45, 52, 61, 58, 70, 78, 85, 92, 88, 95, 102, 110];

        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Pacientes',
                    data: pacientes,
                    borderColor: '#3C0061',
                    backgroundColor: 'rgba(60, 0, 97, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3C0061',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#3C0061',
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
                                return context.parsed.y + ' pacientes';
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

    // GRÁFICO 3: Gênero (Donut)
    const ctxGenero = document.getElementById('graficoDonutGenero');
    if (ctxGenero) {
        const homens = {{ $dadosGenero['Homens'] ?? 45 }};
        const mulheres = {{ $dadosGenero['Mulheres'] ?? 55 }};

        new Chart(ctxGenero, {
            type: 'doughnut',
            data: {
                labels: ['Homens', 'Mulheres'],
                datasets: [{
                    data: [homens, mulheres],
                    backgroundColor: ['#3C0061', '#DC2626'],
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
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Animação suave ao carregar
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.slide-up, .fade-in, .zoom-in');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, index * 100);
        });
    });
</script>

@endsection