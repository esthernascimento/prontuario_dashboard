@extends('enfermeiro.templates.enfermeiroTemplate') 

@section('title', 'Prontuário de ' . ($paciente->nomePaciente ?? 'Paciente'))

@section('content')

<body>
    {{-- CSS Original para a VISÃO DE ENFERMAGEM. Verifique se este arquivo CSS está completo e formatado. --}}
    <link rel="stylesheet" href="{{ asset('css/enfermeiro/visualizarProntuarioEnfermeiro.css') }}">
<main class="main-dashboard">
    <div class="prontuario-container">
        
        {{-- Cabeçalho Principal --}}
        <div class="header-prontuario">
            {{-- TÍTULO ORIGINAL: Prontuário de Enfermagem --}}
            <h1><i class="bi bi-person-badge-fill"></i> Prontuário de Enfermagem</h1>
            
            {{-- ROTA ORIGINAL: enfermeiro.anotacao.create --}}
            <a href="{{ route('enfermeiro.anotacao.create', $paciente->idPaciente) }}" class="btn-nova-anotacao">
                <i class="bi bi-plus-circle-fill"></i> Nova Anotação
            </a>
        </div>

        {{-- Cartão de Informações do Paciente (Esta é a seção que você se refere ao "card de informações") --}}
        <div class="card-paciente-info">
            <div class="paciente-header">
                <i class="bi bi-person-circle profile-icon"></i>
                <h2>{{ $paciente->nomePaciente ?? 'Paciente Não Encontrado' }}</h2>
                <span class="status-badge status-ativo">Ativo</span>
                <span class="prontuario-numero">Prontuário Nº {{ $paciente->idPaciente ?? '00000' }}</span>
            </div>
            
            <div class="paciente-stats">
                <div>
                    <strong>CPF</strong>
                    <span>{{ $paciente->cpfPaciente ?? 'N/A' }}</span>
                </div>
                <div>
                    <strong>DATA DE NASCIMENTO</strong>
                    <span>{{ $paciente->dataNascPaciente ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div>
                    <strong>IDADE</strong>
                    <span>{{ isset($paciente->dataNascPaciente) ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->age : 'N/A' }} anos</span>
                </div>
                <div>
                    {{-- VARIÁVEL E RÓTULO ORIGINAIS: $anotacoes --}}
                    <strong>TOTAL DE ANOTAÇÕES</strong>
                    <span>{{ $anotacoes->count() ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Histórico de Anotações de Enfermagem --}}
        <div class="historico-container">
            {{-- TÍTULO ORIGINAL --}}
            <h3><i class="bi bi-clock-history"></i> Histórico de Anotações de Enfermagem</h3>
            
            {{-- LOOP ORIGINAL: $anotacoes como $anotacao --}}
            @forelse ($anotacoes ?? [] as $anotacao)
                {{-- O card individual agora usa a estrutura do modelo médico --}}
                {{-- Adicionei a classe 'anotacao-card-medico' para estilizar este item como um card, se necessário no seu CSS --}}
                <div class="anotacao-item anotacao-card-medico">
                    <div class="anotacao-timeline-line"></div>
                    <div class="anotacao-info">
                        
                        {{-- NOVO HEADER DO CARD, adaptado para a cor vermelha (se você definir a classe 'header-persona-vermelha' no seu CSS) --}}
                        <div class="anotacao-header header-persona-vermelha">
                            <div class="anotacao-data">
                                {{ \Carbon\Carbon::parse($anotacao->data_hora ?? $anotacao->created_at)->format('d/m/Y H:i') }}
                            </div>
                            <div class="anotacao-header-content">
                                <strong>{{ $anotacao->tipo_registro ?? 'Registro Manual' }}</strong>
                                <span class="enfermeiro-info">
                                    <i class="bi bi-person-badge"></i> {{ $anotacao->enfermeiro->nomeEnfermeiro ?? 'Enfermeiro(a) Desconhecido' }}
                                </span>
                            </div>
                        </div>

                        {{-- DETALHES CLÍNICOS E SINAIS VITAIS --}}
                        <div class="anotacao-details">

                            {{-- Linha de Sinais Vitais (Mantida no topo) --}}
                            @if ($anotacao->temperatura || $anotacao->pressao_arterial || $anotacao->frequencia_cardiaca)
                                <div class="sinais-vitais mb-4">
                                    <strong>Sinais Vitais:</strong>
                                    @if ($anotacao->temperatura) <span><i class="bi bi-thermometer-half"></i> Temp: **{{ $anotacao->temperatura }}°C**</span> @endif
                                    @if ($anotacao->pressao_arterial) <span><i class="bi bi-heart-pulse-fill"></i> PA: **{{ $anotacao->pressao_arterial }}**</span> @endif
                                    @if ($anotacao->frequencia_cardiaca) <span><i class="bi bi-activity"></i> FC: **{{ $anotacao->frequencia_cardiaca }} bpm**</span> @endif
                                </div>
                            @endif

                            {{-- ESTRUTURA DE CARDS DE INFORMAÇÃO (Alergias, Medicações, Observações) --}}
                            <div class="detalhes-cards-grid">
                                
                                {{-- Card 1: Alergias (Substitui Observações) --}}
                                <div class="detalhe-card">
                                    <strong><i class="bi bi-bug-fill text-danger"></i> Alergias Identificadas</strong>
                                    <p>{{ $anotacao->alergias ?? 'Nenhuma alergia registrada.' }}</p>
                                </div>

                                {{-- Card 2: Medicações/Procedimentos (Substitui Exames Solicitados) --}}
                                <div class="detalhe-card">
                                    <strong><i class="bi bi-bandages-fill"></i> Medicações e/ou Procedimentos</strong>
                                    <p>{{ $anotacao->medicacoes_procedimentos ?? 'Nenhuma medicação/procedimento registrado.' }}</p>
                                </div>

                                {{-- Card 3: Descrição/Evolução (Substitui Medicamentos Prescritos) --}}
                                <div class="detalhe-card">
                                    <strong><i class="bi bi-file-earmark-text-fill"></i> Descrição da Anotação/Evolução</strong>
                                    <p>{{ $anotacao->descricao ?? 'Nenhuma descrição fornecida.' }}</p>
                                </div>
                            </div>

                            <span class="unidade mt-3 d-block">Unidade: {{ $anotacao->unidade_atendimento ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                {{-- O bloco @empty permanece para quando não houver registros --}}
                <div class="no-records">
                    <p><i class="bi bi-info-circle-fill"></i> Nenhuma anotação de enfermagem encontrada para este paciente.</p>
                    <a href="{{ route('enfermeiro.anotacao.create', $paciente->idPaciente) }}" class="btn-sm-primary">
                        Registrar a Primeira Anotação
                    </a>
                </div>
            @endforelse
        </div>
        
    </div>
</main>
</body>
@endsection
