@extends('unidade.templates.unidadeTemplate')

@section('title', 'Dashboard Unidade')

@section('content')

<link rel="stylesheet" href="{{ asset('css/unidade/dashboardUnidade.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@php
    // $nomeUnidade é enviado pelo controller "limpo"
@endphp

<div class="overview-container">
    
    <!-- Corrigido: /h1> para </h1> e título ajustado -->
    <h1 class="dashboard-title fade-in" style="animation-delay: 0.2s;">Dashboard da Unidade</h1>

    <div class="welcome-banner zoom-in-banner" style="animation-delay: 0.4s;">
        <div class="banner-left">
            <div class="banner-logo-container">
                <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
            </div>
        </div>
        <div class="banner-center">
            <!-- $nomeUnidade é fornecido pelo controller "limpo" -->
            <h2>Bem-vindo(a) <span class="name">{{ $nomeUnidade ?? 'Usuário' }}</span></h2>
            <p>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
        </div>
        <div class="banner-right">
            <img src="{{ asset('img/ministerio.png') }}" alt="Ilustração de Médicos" class="funcionarios-image">
        </div>
    </div>

    <div class="metrics">
        <!-- Card Médicos (OK) -->
        <div class="metric-card slide-up" style="animation-delay: 0.6s;">
            <div class="container-img">
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Ícone de Médico">
                <span>Médicos cadastrados</span>
            </div>
            <!-- Adicionado <strong> de volta (controller envia $medicosCount = 0) -->
            <strong>{{ $medicosCount ?? 0 }}</strong>
        </div>

        <!-- Card Enfermeiros (OK) -->
        <div class="metric-card slide-up" style="animation-delay: 0.8s;">
            <div class="container-img">
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Ícone de Enfermeiro">
                <span>Enfermeiros cadastrados</span>
            </div>
            <!-- Adicionado <strong> de volta (controller envia $nursesCount = 0) -->
            <strong>{{ $nursesCount ?? 0 }}</strong>
        </div>

        <!-- Card Pacientes (Adicionado, pois o controller envia $patientsCount = 0) -->
        <div class="metric-card slide-up" style="animation-delay: 1.0s;">
            <div class="container-img">
                <img src="{{ asset('img/usuario-de-perfil.png') }}" alt="Ícone de Paciente">
                <span>Recepcionista cadastrados</span>
            </div>
            <strong>{{ $patientsCount ?? 0 }}</strong>
        </div>

        <!-- Card Unidades (REMOVIDO, pois $unidadesCount não é enviado pelo controller limpo) -->
    </div>

    <!-- 
      ADICIONADO: HTML dos Gráficos (necessário para o script JS abaixo)
      Eles aparecerão vazios, pois os dados do controller estão vazios.
    -->
    <div class_charts_container="charts-container">
        <div class="chart-card fade-in" style="animation-delay: 1.2s;">
            <h3>Médicos por Especialidade</h3>
            <canvas id="graficoEspecialidades"></canvas>
        </div>
        <div class="chart-card fade-in" style="animation-delay: 1.4s;">
            <h3>Crescimento de Pacientes</h3>
            <canvas id="graficoLinha"></canvas>
        </div>
        <div class="chart-card-small fade-in" style="animation-delay: 1.6s;">
            <h3>Gênero dos Pacientes</h3>
            <canvas id="graficoDonutGenero"></canvas>
        </div>
    </div>

</div> <!-- Fim do overview-container -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // =========================================================================
    // O SCRIPT ABAIXO VAI FUNCIONAR SEM ERROS
    // Ele receberá os dados vazios (como 0 ou []) do controller
    // e simplesmente renderizará os gráficos vazios.
    // =========================================================================

    // Gráfico 1: Especialidades
    const ctxEspecialidade = document.getElementById('graficoEspecialidades');
    if (ctxEspecialidade) {
        new Chart(ctxEspecialidade, {
            type: 'bar',
            data: {
                // $medicosPorEspecialidade é uma coleção vazia
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
                        max: 100, // Você pode ajustar se quiser
                        ticks: { stepSize: 10 },
                        grid: { color: '#f0f0f0' }
                    },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#000' },
                    title: {
                        display: false, // O H3 já faz o título
                        text: 'Quantidade de Médicos por Especialidade',
                    }
                }
            }
        });
    }

    // Gráfico 2: Linha (Pacientes)
    const ctxLinha = document.getElementById('graficoLinha');
    if (ctxLinha) {
        const dadosLinha = @json($dadosLinha); // $dadosLinha está vazio
        new Chart(ctxLinha, {
            type: 'line',
            data: {
                labels: dadosLinha.meses,
                datasets: [{
                    label: 'Pacientes',
                    data: dadosLinha.pacientes,
                    borderColor: '#0618b9',
                    backgroundColor: '#E9ECFF',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 195, ticks: { stepSize: 10 } }
                },
                plugins: {
                     title: {
                        display: false, // O H3 já faz o título
                        text: 'Crescimento de Cadastro de Pacientes',
                    }
                }
            }
        });
    }

    // Gráfico 3: Gênero
    const ctxGenero = document.getElementById('graficoDonutGenero');
    if (ctxGenero) {
        new Chart(ctxGenero, {
            type: 'pie',
            data: {
                labels: ['Homens', 'Mulheres'],
                // $dadosGenero envia [0, 0]
                datasets: [{
                    data: [{{ $dadosGenero['Homens'] ?? 0 }}, {{ $dadosGenero['Mulheres'] ?? 0 }}],
                    backgroundColor: ['#0000ff', '#ff0066']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'center',
                        labels: { usePointStyle: true }
                    }
                }
            }
        });
    }
</script>
@endsection
