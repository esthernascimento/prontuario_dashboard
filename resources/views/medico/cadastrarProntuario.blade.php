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
                <div class="info-item">
                    <strong>Data e Hora da Consulta:</strong>
                    <span>{{ isset($consulta) && $consulta->dataConsulta ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('d/m/Y H:i') : \Carbon\Carbon::now()->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <strong>Unidade de Atendimento:</strong>
                    <span>{{ $unidadeMedico->nomeUnidade ?? 'Não especificada' }}</span>
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
                    <i class="bi bi-calendar-check"></i> Data e Hora da Consulta *
                </label>
                <input
                    type="datetime-local"
                    id="dataConsulta"
                    name="dataConsulta"
                    value="{{ old('dataConsulta', isset($consulta) && $consulta->dataConsulta ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('Y-m-d\TH:i') : \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                    required
                    class="input-datetime"
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

            {{-- EXAMES COM CHECKBOX E TIPO --}}
            <div class="input-group">
                <label>
                    <i class="bi bi-clipboard2-pulse"></i> Exames Solicitados
                </label>

                <input type="text" id="filtroExames" class="input-filtro" placeholder="Pesquisar exame...">

                <div id="listaExames" class="checkbox-list">
                    @php
                        $tiposExame = [
                            'Análises Clínicas', 'Imagem', 'Endoscopia', 'Biopsia', 'Testes Funcionais', 'Outros'
                        ];
                        
                        $exames = [
                            'Hemograma Completo', 'Glicemia de Jejum', 'Colesterol Total e Frações', 'Triglicerídeos',
                            'Creatinina', 'Ureia', 'Ácido Úrico', 'TGO (AST)', 'TGP (ALT)', 'Bilirrubinas',
                            'Electroforese de Proteínas', 'Proteína C-Reativa (PCR)', 'VHS', 'Urinalise',
                            'Eletrocardiograma (ECG)', 'Radiografia de Tórax', 'Ultrassom Abdominal',
                            'Ultrassom Ginecológico', 'Ultrassom Obstétrico', 'Mamografia',
                            'Citologia Oncótica (Papanicolau)', 'Teste Rápido HIV', 'Teste Rápido Sífilis',
                            'Teste Rápido Hepatite B e C', 'Teste de Gravidez', 'Coagulograma', 'TP/INR',
                            'TSH', 'T4 Livre', 'FT3', 'Anticorpos Anti-TPO', 'HbA1c', 'Microalbuminúria',
                            'Cultura de Urina', 'Cultura de Fezes', 'Parasitológico de Fezes', 'Exame de Fezes',
                            'Exame de Sangue Oculto nas Fezes', 'Beta-HCG', 'FSH', 'LH', 'Prolactina',
                            'Testosterona', 'Estradiol', 'Progesterona', 'PSA Total', 'Vitaminas (B12, D)',
                            'Ferro Sérico', 'Ferritina', 'Transferrina', 'Ácido Fólico', 'Calcium Iônico',
                            'Magnésio', 'Fósforo', 'Sódio', 'Potássio', 'Cloro', 'Bicarbonato',
                            'Gasometria Arterial', 'Gasometria Venosa', 'Ecocardiograma', 'Doppler Venoso',
                            'Doppler Arterial', 'Endoscopia Digestiva Alta', 'Colonoscopia', 'Colposcopia',
                            'Biopsia de Pele', 'Biopsia de Mama', 'Biopsia de Colo Uterino', 'Teste de Esforço',
                            'Holter 24h', 'Mapa 24h', 'Polissonografia', 'Teste de Função Pulmonar',
                            'Teste de Audição', 'Teste de Visão', 'Teste de Glicose Tolerância Oral (OGTT)',
                            'Teste de Estresse', 'Teste de Esforço Cardíaco', 'Teste de Função Hepática',
                            'Teste de Função Renal', 'Teste de Função Tireoidiana', 'Teste de Função Adrenal',
                            'Teste de Função Gonadal', 'Teste de Função Pancreática', 'Outros'
                        ];
                    @endphp

                    @foreach($exames as $exame)
                        <div class="exame-item" data-exame="{{ $exame }}">
                            <label class="checkbox-item">
                                <input type="checkbox" name="exames_solicitados[]" value="{{ $exame }}"
                                    {{ is_array(old('exames_solicitados')) && in_array($exame, old('exames_solicitados')) ? 'checked' : '' }}
                                    onchange="toggleExameDetails('{{ $exame }}', this.checked)">
                                {{ $exame }}
                            </label>
                            
                            <div class="exame-details" id="exame-details-{{ str_replace(' ', '-', $exame) }}" style="display: none;">
                                <div class="exame-detail-row">
                                    <label for="tipo-exame-{{ str_replace(' ', '-', $exame) }}">Tipo de Exame:</label>
                                    <select name="exame_tipos[{{ $exame }}]" id="tipo-exame-{{ str_replace(' ', '-', $exame) }}" class="exame-select">
                                        @foreach($tiposExame as $tipo)
                                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('exames_solicitados')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            {{-- MEDICAMENTOS COM CHECKBOX E DETALHES --}}
            <div class="input-group">
                <label>
                    <i class="bi bi-capsule-pill"></i> Medicamentos Prescritos
                </label>

                <input type="text" id="filtroMedicamentos" class="input-filtro" placeholder="Pesquisar medicamento...">

                <div id="listaMedicamentos" class="checkbox-list">
                    @php
                        $tiposMedicamento = [
                            'Analgésico', 'Anti-inflamatório', 'Antibiótico', 'Antiviral', 'Antifúngico', 
                            'Antihistamínico', 'Broncodilatador', 'Corticosteroide', 'Anti-hipertensivo', 
                            'Diurético', 'Antidiabético', 'Anticonvulsivante', 'Antidepressivo', 'Ansiolítico', 
                            'Vitamina', 'Suplemento', 'Outros'
                        ];
                        
                        $medicamentos = [
                            'Paracetamol', 'Dipirona', 'Ibuprofeno', 'Amoxicilina', 'Azitromicina', 
                            'Cefalexina', 'Ciprofloxacino', 'Omeprazol', 'Pantoprazol', 'Ranitidina',
                            'Metoclopramida', 'Bromoprida', 'Domperidona', 'Diclofenaco', 'Nimesulida',
                            'Prednisona', 'Dexametasona', 'Loratadina', 'Desloratadina', 'Cetirizina',
                            'Captopril', 'Losartana', 'Enalapril', 'Sinvastatina', 'Atorvastatina',
                            'Metformina', 'Glibenclamida', 'Levotiroxina', 'Sulfato Ferroso',
                            'Vitamina C', 'Complexo B', 'Soro Fisiológico', 'Glicose 5%', 'Ringer Lactato',
                            'Outros'
                        ];
                    @endphp

                    @foreach($medicamentos as $medicamento)
                        <div class="medicamento-item" data-medicamento="{{ $medicamento }}">
                            <label class="checkbox-item">
                                <input type="checkbox" name="medicamentos_prescritos[]" value="{{ $medicamento }}"
                                    {{ is_array(old('medicamentos_prescritos')) && in_array($medicamento, old('medicamentos_prescritos')) ? 'checked' : '' }}
                                    onchange="toggleMedicamentoDetails('{{ $medicamento }}', this.checked)">
                                {{ $medicamento }}
                            </label>
                            
                            <div class="medicamento-details" id="medicamento-details-{{ str_replace(' ', '-', $medicamento) }}" style="display: none;">
                                <div class="medicamento-detail-row">
                                    <label for="tipo-medicamento-{{ str_replace(' ', '-', $medicamento) }}">Tipo:</label>
                                    <select name="medicamento_tipos[{{ $medicamento }}]" id="tipo-medicamento-{{ str_replace(' ', '-', $medicamento) }}" class="medicamento-select">
                                        @foreach($tiposMedicamento as $tipo)
                                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="medicamento-detail-row">
                                    <label for="dosagem-medicamento-{{ str_replace(' ', '-', $medicamento) }}">Dosagem:</label>
                                    <input type="text" name="medicamento_dosagens[{{ $medicamento }}]" id="dosagem-medicamento-{{ str_replace(' ', '-', $medicamento) }}" class="medicamento-input" placeholder="Ex: 500mg">
                                </div>
                                
                                <div class="medicamento-detail-row">
                                    <label for="frequencia-medicamento-{{ str_replace(' ', '-', $medicamento) }}">Frequência:</label>
                                    <input type="text" name="medicamento_frequencias[{{ $medicamento }}]" id="frequencia-medicamento-{{ str_replace(' ', '-', $medicamento) }}" class="medicamento-input" placeholder="Ex: 8/8 horas">
                                </div>
                                
                                <div class="medicamento-detail-row">
                                    <label for="periodo-medicamento-{{ str_replace(' ', '-', $medicamento) }}">Período:</label>
                                    <input type="text" name="medicamento_periodos[{{ $medicamento }}]" id="periodo-medicamento-{{ str_replace(' ', '-', $medicamento) }}" class="medicamento-input" placeholder="Ex: 5 dias">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('medicamentos_prescritos')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

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

                {{-- BOTÃO ALTERADO PARA ABRIR O MODAL DE CONFIRMAÇÃO --}}
                <button type="button" class="save-button" onclick="openConfirmModal()">
                    <i class="bi bi-check-circle"></i> {{ isset($consulta) ? 'Finalizar Atendimento' : 'Salvar Prontuário' }}
                </button>
            </div>
        </form>
    </div>
</main>

{{-- MODAL DE CONFIRMAÇÃO --}}
<div id="confirmModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <h2>Confirmar Finalização</h2>
        </div>
        <div class="modal-body">
            <p>Tem certeza que deseja finalizar este atendimento? Após a confirmação, o paciente será encaminhado e a consulta não poderá mais ser editada por aqui.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel-modal" onclick="closeConfirmModal()">Cancelar</button>
            <button type="button" class="btn-confirm-modal" onclick="submitForm()">Confirmar e Finalizar</button>
        </div>
    </div>
</div>

{{-- MODAL CUSTOMIZADO DE PDF --}}
@if(isset($consulta))
<div id="pdfOptionsModal" class="modal-overlay-pdf">
    <div class="modal-content-pdf">
        <div class="modal-header-pdf">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <h2>Selecione o Documento para Baixar</h2>
            <p>Escolha qual PDF você deseja gerar</p>
        </div>

        <div class="modal-body-pdf">
            {{-- OPÇÃO 1: Pedido de Exames --}}
            @php
                $examesCheckboxes = is_array(old('exames_solicitados')) ? old('exames_solicitados') : [];
                $examesBanco = isset($consulta->examesSolicitados) && trim($consulta->examesSolicitados) !== '' ? explode("\n", $consulta->examesSolicitados) : [];
                $temExames = count($examesCheckboxes) > 0 || count($examesBanco) > 0;
            @endphp
            
            <div id="examesPdfOption" class="pdf-modal-option {{ $temExames ? '' : 'disabled' }}">
                <div class="option-icon">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
                <div class="pdf-modal-info">
                    <h3>Pedido de Exames</h3>
                    <p id="examesStatusText">
                        {{ $temExames ? 'Documento com a lista de exames solicitados para o paciente.' : 'Nenhum exame foi solicitado ainda. Selecione os exames desejados na lista acima.' }}
                    </p>
                </div>
                <span class="pdf-status-badge {{ $temExames ? 'disponivel' : 'indisponivel' }}">
                    <i class="bi bi-{{ $temExames ? 'check-circle-fill' : 'x-circle-fill' }}"></i> {{ $temExames ? 'Disponível' : 'Indisponível' }}
                </span>
            </div>

            {{-- OPÇÃO 2: Receita Médica --}}
            @php
                $medicamentosCheckboxes = is_array(old('medicamentos_prescritos')) ? old('medicamentos_prescritos') : [];
                $medicamentosBanco = isset($consulta->medicamentosPrescritos) && trim($consulta->medicamentosPrescritos) !== '' ? explode("\n", $consulta->medicamentosPrescritos) : [];
                $temMedicamentos = count($medicamentosCheckboxes) > 0 || count($medicamentosBanco) > 0;
            @endphp
            
            <div id="receitaPdfOption" class="pdf-modal-option {{ $temMedicamentos ? '' : 'disabled' }}">
                <div class="option-icon">
                    <i class="bi bi-prescription2"></i>
                </div>
                <div class="pdf-modal-info">
                    <h3>Receita Médica</h3>
                    <p id="receitaStatusText">
                        {{ $temMedicamentos ? 'Documento com a lista de medicamentos prescritos e instruções.' : 'Nenhum medicamento foi prescrito ainda. Selecione os itens desejados na lista acima.' }}
                    </p>
                </div>
                <span class="pdf-status-badge {{ $temMedicamentos ? 'disponivel' : 'indisponivel' }}">
                    <i class="bi bi-{{ $temMedicamentos ? 'check-circle-fill' : 'x-circle-fill' }}"></i> {{ $temMedicamentos ? 'Disponível' : 'Indisponível' }}
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
// ===== FUNÇÕES DO MODAL DE PDF =====
function openPdfModal() {
    updatePdfOptions();
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

// Função que verifica se os PDFs podem ser gerados (em tempo real)
function updatePdfOptions() {
    const examesCheckboxes = document.querySelectorAll('input[name="exames_solicitados[]"]:checked');
    const medicamentosCheckboxes = document.querySelectorAll('input[name="medicamentos_prescritos[]"]:checked');

    // --- Lógica de Exames ---
    const examesPdfOption = document.getElementById('examesPdfOption');
    const examesStatusText = document.getElementById('examesStatusText');

    if (examesPdfOption) {
        if (examesCheckboxes.length > 0) {
            examesPdfOption.classList.remove('disabled');
            examesPdfOption.classList.add('enabled');
            examesStatusText.textContent = 'Documento com a lista de exames solicitados para o paciente.';

            const badge = examesPdfOption.querySelector('.pdf-status-badge');
            badge.classList.remove('indisponivel');
            badge.classList.add('disponivel');
            badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disponível';

            examesPdfOption.onclick = function() {
                downloadPdf('exames');
            };
        } else {
            examesPdfOption.classList.add('disabled');
            examesPdfOption.classList.remove('enabled');
            examesStatusText.textContent = 'Nenhum exame foi solicitado ainda. Selecione os exames desejados na lista acima.';

            const badge = examesPdfOption.querySelector('.pdf-status-badge');
            badge.classList.add('indisponivel');
            badge.classList.remove('disponivel');
            badge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Indisponível';

            examesPdfOption.onclick = null;
        }
    }

    // --- Lógica da Receita ---
    const receitaPdfOption = document.getElementById('receitaPdfOption');
    const receitaStatusText = document.getElementById('receitaStatusText');

    if (receitaPdfOption) {
        if (medicamentosCheckboxes.length > 0) {
            receitaPdfOption.classList.remove('disabled');
            receitaPdfOption.classList.add('enabled');
            receitaStatusText.textContent = 'Documento com a lista de medicamentos prescritos e instruções.';

            const badge = receitaPdfOption.querySelector('.pdf-status-badge');
            badge.classList.remove('indisponivel');
            badge.classList.add('disponivel');
            badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disponível';

            receitaPdfOption.onclick = function() {
                downloadPdf('receita');
            };
        } else {
            receitaPdfOption.classList.add('disabled');
            receitaPdfOption.classList.remove('enabled');
            receitaStatusText.textContent = 'Nenhum medicamento foi prescrito ainda. Selecione os itens desejados na lista acima.';

            const badge = receitaPdfOption.querySelector('.pdf-status-badge');
            badge.classList.add('indisponivel');
            badge.classList.remove('disponivel');
            badge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Indisponível';

            receitaPdfOption.onclick = null;
        }
    }
}

// Função que aciona o download do PDF
function downloadPdf(type) {
    const optionElement = type === 'exames' ? document.getElementById('examesPdfOption') : document.getElementById('receitaPdfOption');
    const statusBadge = optionElement.querySelector('.pdf-status-badge');
    const optionIcon = optionElement.querySelector('.option-icon');

    if (!optionElement || optionElement.classList.contains('disabled')) {
        return;
    }
    
    // Efeito de loading
    optionIcon.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    statusBadge.innerHTML = '<i class="bi bi-hourglass-split"></i> Gerando...';
    statusBadge.style.background = '#fef3c7';
    statusBadge.style.color = '#92400e';

    let url = '#';
    @if(isset($consulta))
        if (type === 'exames') {
            url = "{{ route('gerarPdfExames', $consulta->idConsultaPK) }}";
        } else if (type === 'receita') {
            url = "{{ route('consulta.receita.pdf', $consulta->idConsultaPK) }}";
        }
    @endif
    
    window.open(url, '_blank'); 
    
    setTimeout(() => {
        closePdfModal();
        showNotification(`${type === 'exames' ? 'Pedido de Exames' : 'Receita Médica'} gerado com sucesso!`, 'success');
        
        // Reverte o ícone
        if (type === 'exames') {
            optionIcon.innerHTML = '<i class="bi bi-clipboard2-pulse"></i>';
        } else if (type === 'receita') {
            optionIcon.innerHTML = '<i class="bi bi-prescription2"></i>'; 
        }
        
    
        statusBadge.style.background = ''; 
        statusBadge.style.color = '';
        updatePdfOptions(); 
    }, 1000);
}

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

document.addEventListener('click', function(event) {
    const modal = document.getElementById('pdfOptionsModal');
    if (modal && event.target === modal) {
        closePdfModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePdfModal();
    }
});

// ===== FUNÇÕES DO MODAL DE CONFIRMAÇÃO =====
function openConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function submitForm() {
    closeConfirmModal();
    document.getElementById('prontuarioForm').submit();
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('confirmModal');
    if (modal && event.target === modal) {
        closeConfirmModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeConfirmModal();

        const pdfModal = document.getElementById('pdfOptionsModal');
        if (pdfModal && pdfModal.classList.contains('active')) {
            closePdfModal();
        }
    }
});

// ===== FUNÇÕES PARA DETALHES DE EXAMES E MEDICAMENTOS =====
function toggleExameDetails(exame, isChecked) {
    const detailsId = 'exame-details-' + exame.replace(/\s+/g, '-');
    const detailsElement = document.getElementById(detailsId);
    
    if (detailsElement) {
        detailsElement.style.display = isChecked ? 'block' : 'none';
    }
}

function toggleMedicamentoDetails(medicamento, isChecked) {
    const detailsId = 'medicamento-details-' + medicamento.replace(/\s+/g, '-');
    const detailsElement = document.getElementById(detailsId);
    
    if (detailsElement) {
        detailsElement.style.display = isChecked ? 'block' : 'none';
    }
}

// ===== CÓDIGO EXISTENTE E LISTENERS PARA ATUALIZAÇÃO =====
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== FILTROS =====
    const filtroMedicamentos = document.getElementById('filtroMedicamentos');
    if (filtroMedicamentos) {
        filtroMedicamentos.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            document.querySelectorAll('#listaMedicamentos .medicamento-item').forEach(item => {
                const texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(termo) ? '' : 'none';
            });
        });
    }

    const filtroExames = document.getElementById('filtroExames');
    if (filtroExames) {
        filtroExames.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            document.querySelectorAll('#listaExames .exame-item').forEach(item => {
                const texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(termo) ? '' : 'none';
            });
        });
    }

    // Adiciona listeners para atualização em tempo real do Modal PDF
    const examesCheckboxes = document.querySelectorAll('input[name="exames_solicitados[]"]');
    examesCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePdfOptions);
    });
    
    const medicamentosCheckboxes = document.querySelectorAll('input[name="medicamentos_prescritos[]"]');
    medicamentosCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePdfOptions);
    });

    // Inicializa detalhes de exames e medicamentos marcados (se houver)
    const examesMarcados = document.querySelectorAll('input[name="exames_solicitados[]"]:checked');
    examesMarcados.forEach(checkbox => {
        toggleExameDetails(checkbox.value, true);
    });
    
    const medicamentosMarcados = document.querySelectorAll('input[name="medicamentos_prescritos[]"]:checked');
    medicamentosMarcados.forEach(checkbox => {
        toggleMedicamentoDetails(checkbox.value, true);
    });

    updatePdfOptions(); 

    console.log('✅ Sistema de Prontuário e PDF customizado carregados com sucesso!');
});
</script>

@endsection