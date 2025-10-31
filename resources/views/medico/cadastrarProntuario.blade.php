@extends('medico.templates.medicoTemplate')

@section('title', isset($consulta) ? 'Finalizar Atendimento' : 'Cadastrar Consulta')

@section('content')

<link rel="stylesheet" href="{{ asset('css/medico/cadastrarProntuario.css') }}">

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            @if (isset($consulta))
                <i class="bi bi-journal-check icon"></i>
                <h1>Finalizar Atendimento</h1>
            @else
                <i class="bi bi-journal-medical icon"></i>
                <h1>Cadastrar Consulta ao Prontuário</h1>
            @endif
        </div>

        {{-- Informações do Paciente e Médico --}}
        <div class="paciente-info">
            <h3><i class="bi bi-person-fill"></i> Informações do Atendimento</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Paciente:</strong>
                    <span>{{ $paciente->nomePaciente }}</span>
                </div>
                <div class="info-item">
                    <strong>CPF:</strong>
                    <span>{{ $paciente->cpfPaciente }}</span>
                </div>
                <div class="info-item">
                    <strong>Data de Nascimento:</strong>
                    <span>{{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <strong>Médico:</strong>
                    <span>{{ $medico->nomeMedico }}</span>
                </div>
                <div class="info-item">
                    <strong>CRM:</strong>
                    <span>{{ $medico->crmMedico }}</span>
                </div>
                <div class="info-item">
                    <strong>Especialidade:</strong>
                    <span>{{ $medico->especialidadeMedico }}</span>
                </div>
                @if (isset($consulta) && $consulta->classificacao_risco)
                    <div class="info-item">
                        <strong>Classificação de Risco:</strong>
                         <span class="status-badge status-{{ $consulta->classificacao_risco }}" style="display:inline-block; margin-top: 5px;">
                            {{ ucfirst($consulta->classificacao_risco) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- BOTÃO PDF DO TOPO - Pedido de Exames --}}
        @if(isset($consulta))
        <div class="pdf-section">
            <div class="pdf-container">
                <div class="pdf-icon">
                    <i class="bi bi-file-earmark-pdf"></i>
                </div>
                <div class="pdf-info">
                    <h4>Pedido de Exames</h4>
                    
                    @if($consulta->examesSolicitados && trim($consulta->examesSolicitados) !== '')
                        <p>Gere o PDF com os exames solicitados para esta consulta</p>
                        <small style="color: var(--success-green); font-weight: 600;">
                            <i class="bi bi-check-circle-fill"></i> Exames disponíveis para download
                        </small>
                    @else
                        <p>Adicione exames solicitados para gerar o PDF</p>
                        <small style="color: var(--text-muted);">
                            <i class="bi bi-exclamation-circle"></i> Nenhum exame cadastrado
                        </small>
                    @endif
                </div>
                
                @if($consulta->examesSolicitados && trim($consulta->examesSolicitados) !== '')
                    <a href="{{ route('medico.gerarPdfExames', $consulta->idConsultaPK) }}" 
                       class="btn-pdf-generate"
                       id="pdf-btn-{{ $consulta->idConsultaPK }}"
                       target="_blank">
                        <i class="bi bi-download"></i>
                        Baixar PDF
                    </a>
                @else
                    <button class="btn-pdf-generate" disabled style="opacity: 0.6; cursor: not-allowed;">
                        <i class="bi bi-download"></i>
                        Sem Exames
                    </button>
                @endif
            </div>
        </div>
        @endif
    
        {{-- Anotações da Enfermagem --}}
        @if (isset($consulta) && $anotacoesEnfermagem && $anotacoesEnfermagem->isNotEmpty())
        <div class="anotacoes-enfermagem-container">
             <h3><i class="bi bi-heart-pulse-fill"></i> Triagem da Enfermagem</h3>
             @foreach ($anotacoesEnfermagem as $anotacao)
                <div class="anotacao-item">
                    <div class="anotacao-header">
                        <span>Registrado em: <strong>{{ \Carbon\Carbon::parse($anotacao->data_hora)->format('d/m/Y H:i') }}</strong></span>
                        <span>Por: <strong>{{ $anotacao->enfermeiro->nomeEnfermeiro ?? 'Enfermeiro(a)' }}</strong></span>
                    </div>
                    <div class="anotacao-body">
                         @if ($anotacao->descricao)
                             <p><strong>Descrição:</strong> {{ $anotacao->descricao }}</p>
                         @endif
                         @if ($anotacao->alergias)
                             <p><strong>Alergias:</strong> {{ $anotacao->alergias }}</p>
                         @endif
                         @if ($anotacao->medicacoes_ministradas)
                             <p><strong>Medicações/Procedimentos:</strong> {{ $anotacao->medicacoes_ministradas }}</p>
                         @endif

                         <div class="sinais-vitais-grid">
                            @if($anotacao->pressao_arterial) <div class="sinal-vital-item"><strong>PA:</strong> {{ $anotacao->pressao_arterial }} mmHg</div> @endif
                            @if($anotacao->temperatura) <div class="sinal-vital-item"><strong>Temp:</strong> {{ $anotacao->temperatura }} °C</div> @endif
                            @if($anotacao->frequencia_cardiaca) <div class="sinal-vital-item"><strong>FC:</strong> {{ $anotacao->frequencia_cardiaca }} bpm</div> @endif
                            @if($anotacao->frequencia_respiratoria) <div class="sinal-vital-item"><strong>FR:</strong> {{ $anotacao->frequencia_respiratoria }} rpm</div> @endif
                            @if($anotacao->saturacao) <div class="sinal-vital-item"><strong>SpO₂:</strong> {{ $anotacao->saturacao }} %</div> @endif
                            @if($anotacao->dor !== null) <div class="sinal-vital-item"><strong>Dor:</strong> {{ $anotacao->dor }}/10</div> @endif
                         </div>
                    </div>
                </div>
             @endforeach
        </div>
        @endif

        {{-- FORMULÁRIO DE CONSULTA --}}
        <form action="{{ isset($consulta) ? route('medico.prontuario.update', $consulta->idConsultaPK) : route('medico.prontuario.store', $paciente->idPaciente) }}" method="POST" id="prontuarioForm">
            @csrf
            @if (isset($consulta))
                @method('PUT')
            @endif

            <div class="form-section-title">Dados da Consulta Médica</div>

            <div class="input-group">
                <label for="dataConsulta">
                    <i class="bi bi-calendar-check"></i> Data da Consulta *
                </label>
                <input
                    type="date"
                    id="dataConsulta"
                    name="dataConsulta"
                    value="{{ old('dataConsulta', isset($consulta) && $consulta->dataConsulta ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('Y-m-d') : date('Y-m-d')) }}"
                    required
                    class="input-date"
                >
                @error('dataConsulta')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="observacoes">
                    <i class="bi bi-file-text"></i> Observações Médicas / Diagnóstico
                </label>
                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="4"
                    placeholder="Descreva o diagnóstico, evolução e observações médicas..."
                >{{ old('observacoes', $consulta->observacoes ?? '') }}</textarea>
                @error('observacoes')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="examesSolicitados">
                    <i class="bi bi-clipboard2-pulse"></i> Exames Solicitados
                </label>
                <textarea
                    id="examesSolicitados"
                    name="examesSolicitados"
                    rows="4"
                    placeholder="Liste os exames solicitados, um por linha...&#10;Exemplos:&#10;Hemograma completo&#10;Raio-X de tórax: PA e perfil&#10;Ultrassom abdominal - avaliar fígado e vesícula"
                >{{ old('examesSolicitados', $consulta->examesSolicitados ?? '') }}</textarea>
                @error('examesSolicitados')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label>
                    <i class="bi bi-capsule-pill"></i> Medicamentos Prescritos
                </label>
                
                <input type="text" id="filtroMedicamentos" class="input-filtro" placeholder="Pesquisar medicamento...">

                <div id="listaMedicamentos" class="checkbox-list">
                    <input type="hidden" id="medicamentosPrescritos" name="medicamentosPrescritos" value="">

                    @php
                        $medicamentos = [
                            'Paracetamol 500mg', 'Paracetamol 750mg', 'Dipirona 500mg', 'Dipirona 1g',
                            'Ibuprofeno 400mg', 'Ibuprofeno 600mg', 'Amoxicilina 500mg', 'Amoxicilina 875mg',
                            'Azitromicina 500mg', 'Cefalexina 500mg', 'Ciprofloxacino 500mg',
                            'Omeprazol 20mg', 'Omeprazol 40mg', 'Pantoprazol 40mg', 'Ranitidina 150mg',
                            'Metoclopramida 10mg', 'Bromoprida 10mg', 'Domperidona 10mg',
                            'Diclofenaco 50mg', 'Diclofenaco Gel', 'Nimesulida 100mg',
                            'Prednisona 5mg', 'Prednisona 20mg', 'Dexametasona 4mg',
                            'Loratadina 10mg', 'Desloratadina 5mg', 'Cetirizina 10mg',
                            'Captopril 25mg', 'Losartana 50mg', 'Enalapril 10mg',
                            'Sinvastatina 20mg', 'Atorvastatina 20mg',
                            'Metformina 500mg', 'Metformina 850mg', 'Glibenclamida 5mg',
                            'Levotiroxina 25mcg', 'Levotiroxina 50mcg', 'Levotiroxina 100mcg',
                            'Soro Fisiológico 0,9%', 'Glicose 5%', 'Ringer Lactato',
                            'Vitamina C', 'Complexo B', 'Sulfato Ferroso',
                            'Outros'
                        ];
                    @endphp

                    @foreach($medicamentos as $medicamento)
                        <label class="checkbox-item">
                            <input type="checkbox" name="medicamentos_prescritos[]" value="{{ $medicamento }}"
                                {{ is_array(old('medicamentos_prescritos')) && in_array($medicamento, old('medicamentos_prescritos')) ? 'checked' : '' }}>
                            {{ $medicamento }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Bloco de posologia removido conforme decisão de descontinuar esse campo --}}

            {{-- BOTÕES DE AÇÃO --}}
            <div class="button-group">
                <a href="{{ route('medico.prontuario') }}" class="btn-cancelar">
                    <i class="bi bi-x-circle"></i> {{ isset($consulta) ? 'Voltar para Fila' : 'Cancelar' }}
                </a>
                
                {{-- BOTÃO QUE ABRE O MODAL CUSTOMIZADO --}}
                @if(isset($consulta))
                <button type="button" class="btn-pdf-download" onclick="openPdfModal()">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Baixar PDF
                </button>
                @endif

                <button type="submit" class="save-button">
                    <i class="bi bi-check-circle"></i> {{ isset($consulta) ? 'Finalizar Atendimento' : 'Salvar Prontuário' }}
                </button>
            </div>
        </form>
    </div>
</main>

{{-- ========================================
     MODAL CUSTOMIZADO DE PDF (Estilo Confirmação)
     ======================================== --}}
@if(isset($consulta))
<div id="pdfOptionsModal" class="modal-overlay-pdf">
    <div class="modal-content-pdf">
        {{-- Header do Modal --}}
        <div class="modal-header-pdf">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <h2>Selecione o Documento para Baixar</h2>
            <p>Escolha qual PDF você deseja gerar</p>
        </div>

        {{-- Body do Modal --}}
        <div class="modal-body-pdf">
            {{-- OPÇÃO 1: Pedido de Exames --}}
            @php
                $examesDisponiveis = $consulta->examesSolicitados && trim($consulta->examesSolicitados) !== '';
            @endphp
            
            @if($examesDisponiveis)
                <a href="{{ route('medico.gerarPdfExames', $consulta->idConsultaPK) }}" 
                   class="pdf-modal-option" 
                   target="_blank"
                   onclick="handlePdfDownload(event)">
                    <div class="option-icon">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <div class="pdf-modal-info">
                        <h3>Pedido de Exames</h3>
                        <p>Documento com a lista de exames solicitados para o paciente</p>
                    </div>
                    <span class="pdf-status-badge disponivel">
                        <i class="bi bi-check-circle-fill"></i> Disponível
                    </span>
                </a>
            @else
                <div class="pdf-modal-option disabled">
                    <div class="option-icon">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <div class="pdf-modal-info">
                        <h3>Pedido de Exames</h3>
                        <p>Nenhum exame foi solicitado ainda. Preencha o campo "Exames Solicitados" e salve.</p>
                    </div>
                    <span class="pdf-status-badge indisponivel">
                        <i class="bi bi-x-circle-fill"></i> Indisponível
                    </span>
                </div>
            @endif

            {{-- OPÇÃO 2: Prescrição Médica --}}
            {{-- OPÇÃO 2: Prescrição Médica removida (posologia descontinuada) --}}
        </div>

        {{-- Footer do Modal --}}
        <div class="modal-footer-pdf">
            <button type="button" onclick="closePdfModal()" class="btn-fechar">
                <i class="bi bi-x-circle"></i> Fechar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ========================================
     JAVASCRIPT CUSTOMIZADO (SEM BOOTSTRAP)
     ======================================== --}}
<script>
// ===== FUNÇÕES DO MODAL DE PDF =====
function openPdfModal() {
    const modal = document.getElementById('pdfOptionsModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closePdfModal() {
    const modal = document.getElementById('pdfOptionsModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function handlePdfDownload(event) {
    const link = event.currentTarget;
    const originalHtml = link.innerHTML;
    
    // Mostra loading
    const optionIcon = link.querySelector('.option-icon');
    const statusBadge = link.querySelector('.pdf-status-badge');
    
    if (optionIcon) {
        optionIcon.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    }
    if (statusBadge) {
        statusBadge.innerHTML = '<i class="bi bi-hourglass-split"></i> Gerando...';
        statusBadge.style.background = '#fef3c7';
        statusBadge.style.color = '#92400e';
    }
    
    // Fecha o modal após 800ms
    setTimeout(() => {
        closePdfModal();
    }, 800);
    
    // Restaura após 3 segundos (caso o usuário volte)
    setTimeout(() => {
        if (optionIcon) {
            optionIcon.innerHTML = originalHtml.match(/<div class="option-icon">[\s\S]*?<\/div>/)[0].replace(/<\/?div[^>]*>/g, '');
        }
        if (statusBadge) {
            statusBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disponível';
            statusBadge.style.background = '#d1fae5';
            statusBadge.style.color = '#065f46';
        }
    }, 3000);
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

// ===== CÓDIGO EXISTENTE =====
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== FILTRO DE MEDICAMENTOS =====
    const filtroMedicamentos = document.getElementById('filtroMedicamentos');
    if (filtroMedicamentos) {
        filtroMedicamentos.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            document.querySelectorAll('#listaMedicamentos .checkbox-item').forEach(item => {
                const texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(termo) ? '' : 'none';
            });
        });
    }

    // ===== FUNÇÃO DE LOADING PARA BOTÃO DO TOPO =====
    const setLoading = (element, isLoading) => {
        if (!element) return;
        
        if (isLoading) {
            const originalHtml = element.innerHTML;
            element.setAttribute('data-original-html', originalHtml);
            element.innerHTML = '<i class="bi bi-hourglass-split"></i> Gerando...';
            element.style.opacity = '0.7';
            element.style.pointerEvents = 'none';
        } else {
            const originalHtml = element.getAttribute('data-original-html');
            if (originalHtml) {
                element.innerHTML = originalHtml;
                element.style.opacity = '1';
                element.style.pointerEvents = 'auto';
                element.removeAttribute('data-original-html');
            }
        }
    };

    // ===== LOADING PARA BOTÃO PDF DO TOPO =====
    const pdfBtnTopo = document.querySelector('.pdf-section .btn-pdf-generate:not([disabled])');
    if (pdfBtnTopo) {
        pdfBtnTopo.addEventListener('click', function() {
            setLoading(this, true);
            setTimeout(() => setLoading(this, false), 5000);
        });
    }

    // Função para validar entradas de texto
    function validarEntrada(texto) {
        if (texto.length < 3) return false; // Muito curto
        if (texto.length > 255) return false; // Muito longo
        
        // Verifica se não é apenas caracteres repetidos
        const uniqueChars = new Set(texto.toLowerCase().split('')).size;
        if (uniqueChars <= 2 && texto.length > 10) return false;
        
        // Verifica se contém pelo menos uma letra
        if (!/[a-zA-ZÀ-ÿ]/.test(texto)) return false;
        
        return true;
    }

    // Compila medicamentos selecionados para campo hidden 'medicamentosPrescritos'
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validar exames solicitados
            const examesTextarea = document.getElementById('examesSolicitados');
            if (examesTextarea && examesTextarea.value.trim()) {
                const linhasExames = examesTextarea.value.split('\n')
                    .map(l => l.trim())
                    .filter(Boolean);
                
                const examesInvalidos = linhasExames.filter(linha => !validarEntrada(linha));
                if (examesInvalidos.length > 0) {
                    e.preventDefault();
                    alert('Alguns exames contêm dados inválidos. Verifique se:\n- Têm pelo menos 3 caracteres\n- Não são apenas caracteres repetidos\n- Contêm pelo menos uma letra');
                    examesTextarea.focus();
                    return false;
                }
            }

            const selecionados = Array.from(document.querySelectorAll('input[name="medicamentos_prescritos[]"]:checked'))
                .map(el => el.value.trim())
                .filter(Boolean);
            const hidden = document.getElementById('medicamentosPrescritos');
            if (hidden) {
                hidden.value = selecionados.join('\n');
            }
        });
    }

    console.log('✅ Sistema de PDF customizado e captura de medicamentos carregados com sucesso!');
});
</script>

@endsection