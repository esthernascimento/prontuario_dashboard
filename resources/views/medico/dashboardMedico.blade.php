@extends('medico.templates.medicoTemplate')

@section('title', 'Dashboard - Prontuário+')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/dashboardMedico.css') }}">

<div class="main-dashboard"> 
    {{-- A tag <main> já está no template, mudamos para <div> --}}
    <h1>OVERVIEW</h1>

    <div class="metrics">
        <div class="metric-card">Médicos cadastrados<br><strong>{{ $adminsCount ?? 0 }}</strong></div>
        <div class="metric-card">Pacientes cadastrados<br><strong>{{ $patientsCount ?? 0 }}</strong></div>
        <div class="metric-card">Exames pendentes<br><strong>{{ $pendingExamsCount ?? 0 }}</strong></div>
    </div>

    <div class="content-wrapper">
        <div id="bar-chart-container" class="chart-container">
            <canvas id="graficoBarras"></canvas>
        </div>
        <div id="line-chart-container" class="chart-container">
            <canvas id="graficoLinha"></canvas>
        </div>

        <div class="info-cards-container">
            <div class="info-card">
                <h3>Índice de gênero</h3>
                <div style="width: 120px; height: 120px;">
                    <canvas id="graficoDonutGenero"></canvas>
                </div>
            </div>
            <div class="info-card">
                <h3>75% IDOSOS</h3>
            </div>
            <div class="info-card">
                <h3>UBS cadastradas</h3>
                <strong>{{ $ubsCount ?? 0 }}</strong>
            </div>
            <div class="info-card">
                <h3>A cada 10 usuários:</h3>
                <p>7 são mulheres<br>3 são homens<br>8 são idosos</p>
            </div>
        </div>
    </div>
</div>
@endsection