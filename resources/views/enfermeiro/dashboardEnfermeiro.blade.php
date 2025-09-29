@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')
<main class="content">
    <div class="page-container">

        <div class="welcome-banner">
        <div class="banner-left">
            {{-- Logo Prontuário+ --}}
            <div class="banner-logo-container">
                {{-- Usando o logo2.png do template (ou ajuste o caminho conforme o logo real) --}}
                <img src="{{ asset('img/enfermeiro-logo2.png') }}" alt="Logo Prontuário+" class="banner-logo">
            </div>
        </div>
        <div class="banner-center">
            <h2>Bem-vindo(a) <span class="name">Enfermeiro(a). {{ $nome ?? 'Esther' }}</span></h2>
            <p>O Prontuário+ fica feliz com a sua presença e dedicação à saúde.</p>
        </div>
        <div class="banner-right">
            {{-- Ilustração de médicos --}}
            <img src="{{ asset('img/enfermeiros.png') }}" alt="Ilustração de Médicos" class="enfermeiros-image">
        </div>
    </div>

        <div class="metrics">
            <div class="metric-card">
                <i class="bi bi-person-fill"></i> Pacientes no sistema
                <strong>5</strong>
            </div>
            <div class="metric-card">
                <i class="bi bi-file-earmark-text-fill"></i> Prontuários disponíveis
                <strong>350</strong>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="charts-left">
                <h4 style="color: #0a400c; margin-bottom: 20px;">Gráfico de Barras</h4>
                <div style="height: 350px; background-color: #f4f6f8; display: flex; flex-direction: column; justify-content: space-around; padding: 10px; border-radius: 10px;">
                    <div style="width: 100%; height: 20px; background-color: #0a400c;"></div>
                    <div style="width: 80%; height: 20px; background-color: #0a400c;"></div>
                    <div style="width: 60%; height: 20px; background-color: #0a400c;"></div>
                    <div style="width: 40%; height: 20px; background-color: #0a400c;"></div>
                </div>
                <p style="text-align: right; color: #0a400c; margin-top: 10px;">Ano de 40</p>
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
                    {{-- O canvas do gráfico foi adicionado aqui --}}
                    <canvas id="graficoDonutEnfermeiro" class="donut-chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Adicione o CDN do Chart.js e o script do gráfico aqui, no final da página --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
                        font: {
                            size: 14
                        },
                        color: '#ffffff'
                    }
                },
                tooltip: {
                    backgroundColor: '#ffffff', // Fundo branco
                    titleColor: '#000000ff', // Cor do título (se houver)
                    bodyColor: '#000000ff', // Cor do texto principal
                    borderColor: '#0a400c', // Cor da borda
                    borderWidth: 1 // Largura da borda
                }
            }
        }
    });
</script>
@endsection