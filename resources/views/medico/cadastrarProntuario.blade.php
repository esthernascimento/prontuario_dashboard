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

            {{-- EXAMES COM CHECKBOX --}}
            <div class="input-group">
                <label>
                    <i class="bi bi-clipboard2-pulse"></i> Exames Solicitados
                </label>

                <input type="text" id="filtroExames" class="input-filtro" placeholder="Pesquisar exame...">

                <div id="listaExames" class="checkbox-list">
                    <input type="hidden" name="exames_solicitados" value="">

                    @php
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
                        <label class="checkbox-item">
                            <input type="checkbox" name="exames_solicitados[]" value="{{ $exame }}"
                                {{ is_array(old('exames_solicitados')) && in_array($exame, old('exames_solicitados')) ? 'checked' : '' }}>
                            {{ $exame }}
                        </label>
                    @endforeach
                </div>
                @error('exames_solicitados')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            {{-- MEDICAMENTOS COM CHECKBOX --}}
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
        
        // Reverte a cor do badge (depois do showNotification)
        statusBadge.style.background = ''; 
        statusBadge.style.color = '';
        updatePdfOptions(); // Garante o status correto após o loading
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

// ===== CÓDIGO EXISTENTE E LISTENERS PARA ATUALIZAÇÃO =====
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== FILTROS =====
    const filtroMedicamentos = document.getElementById('filtroMedicamentos');
    if (filtroMedicamentos) {
        filtroMedicamentos.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            document.querySelectorAll('#listaMedicamentos .checkbox-item').forEach(item => {
                const texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(termo) ? '' : 'none';
            });
            // Não precisa chamar updatePdfOptions aqui, o change do checkbox já faz.
        });
    }

    const filtroExames = document.getElementById('filtroExames');
    if (filtroExames) {
        filtroExames.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            document.querySelectorAll('#listaExames .checkbox-item').forEach(item => {
                const texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(termo) ? '' : 'none';
            });
            // Não precisa chamar updatePdfOptions aqui, o change do checkbox já faz.
        });
    }

    // Removido a lógica do campo hidden pois os checkboxes são enviados diretamente
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Os checkboxes de medicamentos e exames são enviados diretamente pelo formulário
                    // Não precisa de campo hidden adicional
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

    updatePdfOptions(); 

    console.log('✅ Sistema de Prontuário e PDF customizado carregados com sucesso!');
});
</script>

@endsection