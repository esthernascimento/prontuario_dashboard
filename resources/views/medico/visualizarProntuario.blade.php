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
                                        
                                        {{-- NOVA OPÇÃO: Gerar PDFs --}}
                                        <button type="button" class="dropdown-item" onclick="openPdfModal({{ $consulta->idConsultaPK }})">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> Gerar PDFs
                                        </button>
                                        
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

{{-- MODAL CUSTOMIZADO DE PDF --}}
@if(isset($consultas) && $consultas->isNotEmpty())
<div id="pdfOptionsModal" class="modal-overlay-pdf">
    <div class="modal-content-pdf">
        <div class="modal-header-pdf">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <h2>Selecione o Documento para Baixar</h2>
            <p>Escolha qual PDF você deseja gerar</p>
        </div>

        <div class="modal-body-pdf">
            {{-- OPÇÃO 1: Pedido de Exames --}}
            <div id="examesPdfOption" class="pdf-modal-option">
                <div class="option-icon">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
                <div class="pdf-modal-info">
                    <h3>Pedido de Exames</h3>
                    <p id="examesStatusText">Documento com a lista de exames solicitados para o paciente.</p>
                </div>
                <span class="pdf-status-badge disponivel">
                    <i class="bi bi-check-circle-fill"></i> Disponível
                </span>
            </div>

            {{-- OPÇÃO 2: Receita Médica --}}
            <div id="receitaPdfOption" class="pdf-modal-option">
                <div class="option-icon">
                    <i class="bi bi-prescription2"></i>
                </div>
                <div class="pdf-modal-info">
                    <h3>Receita Médica</h3>
                    <p id="receitaStatusText">Documento com a lista de medicamentos prescritos e instruções.</p>
                </div>
                <span class="pdf-status-badge disponivel">
                    <i class="bi bi-check-circle-fill"></i> Disponível
                </span>
            </div>
        </div>

        <div class="modal-footer-pdf">
            <button type="button" onclick="closePdfModal()" class="btn-fechar">
                <i class="bi bi-x-circle"></i> Fechar
            </button>
        </div>
    </div>
</div>
@endif

<script>
// Variável global para armazenar o ID da consulta atual
let currentConsultaId = null;

// ===== FUNÇÕES DO MODAL DE PDF =====
function openPdfModal(consultaId) {
    currentConsultaId = consultaId;
    const modal = document.getElementById('pdfOptionsModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Fecha o dropdown menu
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
}

function closePdfModal() {
    const modal = document.getElementById('pdfOptionsModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    currentConsultaId = null;
}

// download do PDF
function downloadPdf(type) {
    if (!currentConsultaId) {
        showNotification('Erro: ID da consulta não encontrado.', 'error');
        return;
    }

    const optionElement = type === 'exames' ? document.getElementById('examesPdfOption') : document.getElementById('receitaPdfOption');
    const statusBadge = optionElement.querySelector('.pdf-status-badge');
    const optionIcon = optionElement.querySelector('.option-icon');

    // Efeito de loading
    optionIcon.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    statusBadge.innerHTML = '<i class="bi bi-hourglass-split"></i> Gerando...';
    statusBadge.style.background = '#fef3c7';
    statusBadge.style.color = '#92400e';

    let url = '';
    if (type === 'exames') {
        url = "{{ route('gerarPdfExames', '') }}/" + currentConsultaId;
    } else if (type === 'receita') {
        url = "{{ route('consulta.receita.pdf', '') }}/" + currentConsultaId;
    }

    window.location.href = url;

    setTimeout(() => {
        closePdfModal();
        showNotification(`${type === 'exames' ? 'Pedido de Exames' : 'Receita Médica'} gerado com sucesso!`, 'success');

        setTimeout(() => {
            if (type === 'exames') {
                optionIcon.innerHTML = '<i class="bi bi-clipboard2-pulse"></i>';
            } else if (type === 'receita') {
                optionIcon.innerHTML = '<i class="bi bi-prescription2"></i>';
            }
            statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disponível';
            statusBadge.style.background = '';
            statusBadge.style.color = '';
        }, 2000);
    }, 1000);
}

// Sistema de notificações
function showNotification(message, type) {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        document.body.removeChild(existingNotification);
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Event listeners para o modal
document.addEventListener('DOMContentLoaded', function() {
    // Configura os eventos de clique nas opções de PDF
    const examesPdfOption = document.getElementById('examesPdfOption');
    const receitaPdfOption = document.getElementById('receitaPdfOption');

    if (examesPdfOption) {
        examesPdfOption.onclick = function() {
            downloadPdf('exames');
        };
    }

    if (receitaPdfOption) {
        receitaPdfOption.onclick = function() {
            downloadPdf('receita');
        };
    }

    // Fecha modal ao clicar fora
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('pdfOptionsModal');
        if (modal && event.target === modal) {
            closePdfModal();
        }
    });

    // Fecha modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePdfModal();
        }
    });
});

// ===== CÓDIGO EXISTENTE DO DROPDOWN =====
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dos menus dropdown
    const menuToggles = document.querySelectorAll('.btn-menu-toggle');

    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            
            // Fecha outros dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('show');
                }
            });
            
            dropdown.classList.toggle('show');
        });
    });

    // Fecha dropdown ao clicar fora
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });
});

// Mensagens de sucesso/erro
@if(session('success'))
    showNotification('{{ session('success') }}', 'success');
@endif

@if(session('error'))
    showNotification('{{ session('error') }}', 'error');
@endif
</script>

@endsection