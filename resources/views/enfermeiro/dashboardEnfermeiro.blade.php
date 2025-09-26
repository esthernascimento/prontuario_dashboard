@extends('admin.templates.admTemplate')

@section('content')
<main class="content">
    <h1>Bem-vindo(a), {{ $nome }}</h1>

    <div class="metrics">
        <div class="metric-card">Médicos cadastrados<br><strong>{{ $adminsCount ?? 0 }}</strong></div>
        <div class="metric-card">Pacientes cadastrados<br><strong>{{ $patientsCount ?? 0 }}</strong></div>
        <div class="metric-card">Exames pendentes<br><strong>{{ $pendingExamsCount ?? 0 }}</strong></div>
    </div>

    <div class="content-wrapper">
        <div class="info-cards-container">
            <div class="info-card">
                <h3>UBS cadastradas</h3>
                <strong>{{ $ubsCount ?? 0 }}</strong>
            </div>
        </div>
    </div>
</main>
@endsection
