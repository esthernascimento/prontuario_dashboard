@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/dashboardAdm.css') }}">

@php 
  $admin = auth()->guard('admin')->user();

  // Métricas principais
  $metrics = [
    ['title' => 'Médicos cadastrados', 'value' => $adminCount ?? 0],
    ['title' => 'Pacientes cadastrados', 'value' => $patientsCount ?? 0],
    ['title' => 'Exames pendentes', 'value' => $pendingExamsCount ?? 0],
  ];

  // Cards informativos
  $infoCards = [
    ['title' => '75% IDOSOS', 'content' => null],
    ['title' => 'UBS cadastradas', 'content' => $ubsCount ?? 0],
    ['title' => 'A cada 10 usuários:', 'content' => "7 são mulheres<br>3 são homens<br>8 são idosos"],
  ];
@endphp

@section('title', 'Dashboard - Painel Administrativo')

@section('content')
<div class="overview-container">

  {{-- Header --}}
  <div class="overview-header">
    <h1><i class="bi bi-activity"></i> OVERVIEW</h1>
  </div>

  {{-- Cards de métricas --}}
  <div class="metrics">
    @foreach($metrics as $metric)
      <div class="metric-card">
        <span>{{ $metric['title'] }}</span>
        <strong>{{ $metric['value'] }}</strong>
      </div>
    @endforeach
  </div>

  {{-- Conteúdo com gráficos e info cards --}}
  <div class="content-wrapper">

    {{-- Gráficos --}}
    <div class="charts">
      <div class="chart-container">
        <canvas id="graficoBarras"></canvas>
      </div>
      <div class="chart-container">
        <canvas id="graficoLinha"></canvas>
      </div>
    </div>

    {{-- Info cards --}}
    <div class="info-cards-container">
      
      {{-- Card com gráfico donut --}}
      <div class="info-card">
        <h3>Índice de gênero</h3>
        <div class="donut-chart">
          <canvas id="graficoDonutGenero"></canvas>
        </div>
      </div>

      {{-- Outros cards --}}
      @foreach($infoCards as $card)
        <div class="info-card">
          <h3>{{ $card['title'] }}</h3>
          @if($card['content'])
            <p>{!! $card['content'] !!}</p>
          @endif
        </div>
      @endforeach

    </div>
  </div>
</div>
@endsection
