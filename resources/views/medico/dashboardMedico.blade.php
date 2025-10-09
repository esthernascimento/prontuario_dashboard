@extends('medico.templates.medicoTemplate')

@section('title', 'Dashboard - Prontuário+')

@section('content')

<link rel="stylesheet" href="{{ asset('css/medico/dashboardMedico.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-dashboard">
    {{-- Título principal --}}
    <h1 class="dashboard-title">Dashboard Médico</h1>

    {{-- 1. Banner de Boas-vindas (Full Width) --}}
    <div class="welcome-banner">
        <div class="banner-left">
            {{-- Logo Prontuário+ --}}
            <div class="banner-logo-container">
                <img src="{{ asset('img/medico-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
            </div>
        </div>
        <div class="banner-center">
            <h2>Bem-vindo(a) <span class="doctor-name">Dr(a). {{ $nome ?? 'Médico' }}</span></h2>
            <p>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
        </div>
        <div class="banner-right">
            {{-- Ilustração de médicos --}}
            <img src="{{ asset('img/funcionarios.png') }}" alt="Ilustração de Médicos" class="funcionarios-image">
        </div>
    </div>

    {{-- 2. Cards de Métricas Quadrados e Logo SUS --}}
    <div class="main-metrics-wrapper">
        <div class="metric-square-container">
            <div class="metric-square">
                <img src="{{ asset('img/icon-pessoa.png') }}" alt="Ícone Pacientes" class="icon-metric">
                {{-- Valor do banco de dados --}}
                <strong>{{ $patientsCount }}</strong>
                <span>Pacientes ativos</span>
            </div>
            <div class="metric-square">
                <img src="{{ asset('img/icon-prontuario.png') }}" alt="Ícone Prontuários" class="icon-metric">
                {{-- Valor do banco de dados --}}
                <strong>{{ $prontuariosCount }}</strong>
                <span>Prontuários registrados</span>
            </div>
        </div>

        {{-- Logo SUS (Separado, ao lado dos cards) --}}
        <div class="sus-logo-container">
            <i class="bi bi-hospital sus-icon"></i>
            <span class="sus-text">SUS</span>
            <p>Sistema Único de Saúde</p>
        </div>
    </div>

    {{-- 3. Área de Gráficos --}}
    <div class="chart-and-logo-wrapper">
        {{-- Coluna Esquerda: Gráfico de Barras (Menor) --}}
        <div class="chart-column-left">
            <div id="bar-chart-container" class="chart-container">
                <h3 class="chart-title">Atendimentos por Mês</h3>
                <canvas id="graficoBarras"></canvas>
            </div>
        </div>

        {{-- Coluna Direita: Gráfico de Linha (Maior) --}}
        <div class="chart-column-right">
            <div id="line-chart-container" class="chart-container">
                <h3 class="chart-title">Evolução de Atendimentos</h3>
                <canvas id="graficoLinha"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Dados para o Gráfico de Barras (Atendimentos por Mês)
    const atendimentosData = @json($atendimentosPorMes);
    const labelsBarras = atendimentosData.map(item => {
        const date = new Date(null, item.mes - 1);
        return date.toLocaleString('default', { month: 'short' });
    });
    const dataBarras = atendimentosData.map(item => item.total);

    const ctxBarras = document.getElementById('graficoBarras').getContext('2d');
    new Chart(ctxBarras, {
        type: 'bar',
        data: {
            labels: labelsBarras,
            datasets: [{
                label: 'Atendimentos',
                data: dataBarras,
                backgroundColor: 'rgba(140, 16, 7, 0.8)',
                borderColor: 'rgba(123, 14, 6, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Atendimentos'
                    }
                }
            }
        }
    });

    // Dados para o Gráfico de Linha (Evolução de Atendimentos)
    const evolucaoData = @json($evolucaoAtendimentos);
    const labelsLinha = evolucaoData.map(item => {
        const date = new Date(item.ano, item.mes - 1);
        return date.toLocaleString('default', { month: 'short', year: '2-digit' });
    });
    const dataLinha = evolucaoData.map(item => item.total);

    const ctxLinha = document.getElementById('graficoLinha').getContext('2d');
    new Chart(ctxLinha, {
        type: 'line',
        data: {
            labels: labelsLinha,
            datasets: [{
                label: 'Total de Atendimentos',
                data: dataLinha,
                borderColor: 'rgba(140, 16, 7, 1)',
                backgroundColor: 'rgba(140, 16, 7, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Atendimentos'
                    }
                }
            }
        }
    });
</script>

@endsection