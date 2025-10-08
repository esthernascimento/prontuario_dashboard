@extends('medico.templates.medicoTemplate')

@section('title', 'Visualizar Prontuário')

@section('content')

<link rel="stylesheet" href="{{ asset('css/medico/visualizarProntuario.css') }}">

<main class="main-dashboard">
<div class="prontuario-wrapper">

    <!-- Header Principal -->
    <div class="page-header">
        <div class="header-left">
            <div class="header-icon">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div class="header-text">
                <h1>Prontuário Médico Eletrônico</h1>
                <p>Visualização completa do histórico de atendimentos</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('medico.cadastrarProntuario', $paciente->idPaciente) }}" class="btn-add-consulta">
                <i class="bi bi-plus-lg"></i>
                <span>Nova Consulta</span>
            </a>
        </div>
    </div>

    <!-- Card de Informações do Paciente -->
    <div class="patient-card">
        <div class="patient-card-header">
            <div class="patient-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="patient-info-main">
                <h2>{{ $paciente->nomePaciente }}</h2>
                <div class="patient-badges">
                    <span class="badge badge-active">
                        <i class="bi bi-check-circle-fill"></i> Ativo
                    </span>
                    @if($prontuario)
                    <span class="badge badge-prontuario">
                        <i class="bi bi-file-medical"></i> Prontuário Nº {{ str_pad($prontuario->idProntuarioPK, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="patient-card-body">
            <div class="info-row">
                <div class="info-col">
                    <div class="info-label">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>CPF</span>
                    </div>
                    <div class="info-value">{{ $paciente->cpfPaciente }}</div>
                </div>
                
                <div class="info-col">
                    <div class="info-label">
                        <i class="bi bi-calendar-event"></i>
                        <span>Data de Nascimento</span>
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') }}
                    </div>
                </div>
                
                <div class="info-col">
                    <div class="info-label">
                        <i class="bi bi-hourglass-split"></i>
                        <span>Idade</span>
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->age }} anos
                    </div>
                </div>
                
                @if($prontuario)
                <div class="info-col">
                    <div class="info-label">
                        <i class="bi bi-folder-plus"></i>
                        <span>Prontuário Aberto</span>
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($prontuario->dataAbertura)->format('d/m/Y') }}
                    </div>
                </div>
                
                <div class="info-col">
                    <div class="info-label">
                        <i class="bi bi-clipboard2-data"></i>
                        <span>Total de Consultas</span>
                    </div>
                    <div class="info-value highlight">
                        {{ $consultas->count() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Seção de Histórico de Consultas -->
    <div class="history-section">
        <div class="history-header">
            <h2>
                <i class="bi bi-clock-history"></i>
                Histórico de Atendimentos
            </h2>
            <span class="consultas-count">
                {{ $consultas->count() }} {{ $consultas->count() === 1 ? 'registro' : 'registros' }}
            </span>
        </div>

        @if($consultas->isEmpty())
            <!-- Estado Vazio -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3>Nenhuma consulta registrada</h3>
                <p>Este paciente ainda não possui atendimentos registrados no sistema.</p>
                <a href="{{ route('medico.cadastrarProntuario', $paciente->idPaciente) }}" class="btn-empty-action">
                    <i class="bi bi-plus-circle-fill"></i>
                    Registrar Primeira Consulta
                </a>
            </div>
        @else
            <!-- Timeline de Consultas -->
            <div class="consultas-list">
                @foreach($consultas as $consulta)
                    <div class="consulta-item">
                        <!-- Linha do tempo -->
                        <div class="timeline-marker">
                            <div class="timeline-dot"></div>
                            @if(!$loop->last)
                            <div class="timeline-line"></div>
                            @endif
                        </div>

                        <!-- Conteúdo da Consulta -->
                        <div class="consulta-content">
                            <!-- Header da Consulta -->
                            <div class="consulta-top">
                                <div class="consulta-date-badge">
                                    <i class="bi bi-calendar3"></i>
                                    <strong>{{ \Carbon\Carbon::parse($consulta->dataConsulta)->format('d/m/Y') }}</strong>
                                </div>
                                <div class="consulta-menu">
                                    <button class="btn-menu-toggle">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('medico.prontuario.edit', $consulta->idConsultaPK) }}" class="dropdown-item">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        {{-- Substituído confirm() por um alerta customizado, mas mantendo a lógica com confirm simples para garantir a funcionalidade em navegadores --}}
                                        <form action="{{ route('medico.prontuario.destroy', $consulta->idConsultaPK) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Deseja realmente excluir esta consulta?')"> 
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash3"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações do Profissional -->
                            <div class="consulta-professional">
                                <div class="professional-avatar">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="professional-info">
                                    <strong>Dr(a). {{ $consulta->nomeMedico }}</strong>
                                    <span>CRM: {{ $consulta->crmMedico }}</span>
                                </div>
                            </div>

                            @if($consulta->unidade)
                            <div class="consulta-location">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>{{ $consulta->unidade }}</span>
                            </div>
                            @endif

                            <!-- Detalhes da Consulta -->
                            <div class="consulta-details">
                                {{-- CORRIGIDO: Usa a estrutura de blocos para formatar os detalhes --}}
                                @if($consulta->observacoes)
                                <div class="detail-block">
                                    <div class="detail-header">
                                        <i class="bi bi-file-text-fill"></i>
                                        <span>Observações</span>
                                    </div>
                                    <div class="detail-content">
                                        {{ $consulta->observacoes }}
                                    </div>
                                </div>
                                @endif

                                @if($consulta->examesSolicitados)
                                <div class="detail-block">
                                    <div class="detail-header">
                                        <i class="bi bi-clipboard2-pulse-fill"></i>
                                        <span>Exames Solicitados</span>
                                    </div>
                                    <div class="detail-content pre-formatted">
                                        {{ $consulta->examesSolicitados }}
                                    </div>
                                </div>
                                @endif

                                @if($consulta->medicamentosPrescritos)
                                <div class="detail-block">
                                    <div class="detail-header">
                                        <i class="bi bi-capsule-pill"></i>
                                        <span>Medicamentos Prescritos</span>
                                    </div>
                                    <div class="detail-content pre-formatted">
                                        {{ $consulta->medicamentosPrescritos }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Footer da Consulta -->
                            <div class="consulta-footer">
                                <small>
                                    <i class="bi bi-clock"></i>
                                    Registrado em {{ \Carbon\Carbon::parse($consulta->created_at)->format('d/m/Y \à\s H:i') }}
                                    @if($consulta->updated_at != $consulta->created_at)
                                        • Atualizado em {{ \Carbon\Carbon::parse($consulta->updated_at)->format('d/m/Y \à\s H:i') }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Botão Voltar -->
    <div class="page-footer">
        <a href="{{ route('medico.prontuario') }}" class="btn-voltar">
            <i class="bi bi-arrow-left-circle"></i>
            <span>Voltar para Lista de Pacientes</span>
        </a>
    </div>

</div>

</main>

<!-- Scripts para o menu dropdown -->

<script>
document.addEventListener('DOMContentLoaded', function() {
// Toggle dos menus dropdown
const menuToggles = document.querySelectorAll('.btn-menu-toggle');

menuToggles.forEach(toggle =&gt; {
    toggle.addEventListener(&#39;click&#39;, function(e) {
        e.stopPropagation();
        const dropdown = this.nextElementSibling;
        
        // Fecha outros dropdowns
        document.querySelectorAll(&#39;.dropdown-menu&#39;).forEach(menu =&gt; {
            if (menu !== dropdown) {
                menu.classList.remove(&#39;show&#39;);
            }
        });
        
        dropdown.classList.toggle(&#39;show&#39;);
    });
});

// Fecha dropdown ao clicar fora
document.addEventListener(&#39;click&#39;, function() {
    document.querySelectorAll(&#39;.dropdown-menu&#39;).forEach(menu =&gt; {
        menu.classList.remove(&#39;show&#39;);
    });
});

});

// Mensagens de sucesso/erro (Manter o estilo com alert() até ter um modal customizado)
@if(session('success'))
alert('{{ session('success') }}');
@endif

@if(session('error'))
alert('{{ session('error') }}');
@endif
</script>

@endsection