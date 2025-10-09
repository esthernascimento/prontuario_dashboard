@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/dashboardEnfermeiro.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<main class="content">
    <div class="page-container">

        <h1 class="dashboard-title">Dashboard Enfermeiro</h1>

        <div class="welcome-banner">
            <div class="banner-left">
                <div class="banner-logo-container">
                    <img src="{{ asset('img/enfermeiro-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
                </div>
            </div>
            <div class="banner-center">
                <h2>Bem-vindo(a) <span class="name">Enfermeiro(a) {{ $nome ?? 'Esther' }}</span></h2>
                <p>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
            </div>
            <div class="banner-right">
                <img src="{{ asset('img/enfermeiros.png') }}" alt="Ilustração de Médicos" class="enfermeiros-image">
            </div>
        </div>

        <div class="metrics">
            <div class="metric-card">
                <i class="bi bi-person-fill"></i> Pacientes no sistema
                {{-- Aqui o valor 5 foi substituído pela variável do banco --}}
                <strong>{{ $patientsCount ?? 0 }}</strong>
            </div>
            <div class="metric-card">
                <i class="bi bi-file-earmark-text-fill"></i> Prontuários disponíveis
                {{-- Aqui o valor 350 foi substituído pela variável do banco --}}
                <strong>{{ $prontuariosCount ?? 0 }}</strong>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="charts-left chart-container">
                <h4 style="color: #0a400c; margin-bottom: 20px;">Gráfico de Pacientes por Mês</h4>
                {{-- O gráfico estático foi substituído por um canvas real --}}
                <canvas id="graficoPacientesMes"></canvas>
            </div>

            <div class="right-column">
                <div class="welcome-card">
                    O SUS agradece a sua <br>colaboração para o nosso sistema!
                    <img src="{{ asset('img/exames.png') }}" alt="Prontuário">
                </div>

                <div class="donut-card">
                    <div class="text-info">
                        Enfermeiros(a) ativos:<br>Homens e Mulheres
                    </div>
                    {{-- O gráfico de donut foi mantido e passará dados do controlador --}}
                    <canvas id="graficoDonutEnfermeiro" class="donut-chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Script para o Gráfico de Donut de Gênero
    const ctxEnfermeiro = document.getElementById('graficoDonutEnfermeiro');
    new Chart(ctxEnfermeiro, {
        type: 'pie',
        data: {
            labels: ['Homens', 'Mulheres'],
            datasets: [{
                data: [
                    {{ $dadosGeneroEnfermeiro['Homens'] ?? 0 }},
                    {{ $dadosGeneroEnfermeiro['Mulheres'] ?? 0 }},
                ],
                backgroundColor: ['#0a400c', '#34a537']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: { size: 14 },
                        color: '#0a400c' // Cor corrigida para o texto da legenda
                    }
                },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#000000',
                    bodyColor: '#000000',
                    borderColor: '#0a400c',
                    borderWidth: 1
                }
            }
        }
    });
    
    // Script para o Gráfico de Barras de exemplo
    const ctxPacientesMes = document.getElementById('graficoPacientesMes');
    new Chart(ctxPacientesMes, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'], // Substitua por labels dinâmicos
            datasets: [{
                label: 'Pacientes Cadastrados',
                data: [12, 19, 3, 5, 2], // Dados de exemplo. Você deve buscar dados reais no controlador.
                backgroundColor: '#0a400c'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Pacientes Cadastrados (Últimos meses)',
                    color: '#0a400c'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection