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

        {{-- DEBUG - VERIFICAR POR QUE O BOTÃO SUMIU --}}
        <div style="background: #fff3cd; padding: 15px; margin: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
            <h4>🔍 DEBUG - Verificando Condições do PDF:</h4>
            <p><strong>Consulta existe:</strong> {{ isset($consulta) ? 'SIM' : 'NÃO' }}</p>
            @if(isset($consulta))
                <p><strong>ID da Consulta:</strong> {{ $consulta->idConsultaPK }}</p>
                <p><strong>Exames Solicitados existe:</strong> {{ $consulta->examesSolicitados ? 'SIM' : 'NÃO' }}</p>
                <p><strong>Conteúdo dos Exames:</strong> "{{ $consulta->examesSolicitados }}"</p>
                <p><strong>Exames está vazio?:</strong> {{ empty($consulta->examesSolicitados) ? 'SIM' : 'NÃO' }}</p>
                <p><strong>Exames trim está vazio?:</strong> {{ trim($consulta->examesSolicitados ?? '') === '' ? 'SIM' : 'NÃO' }}</p>
                <p><strong>Condição 1 (isset):</strong> {{ isset($consulta) ? 'PASSOU' : 'NÃO PASSOU' }}</p>
                <p><strong>Condição 2 (examesSolicitados):</strong> {{ $consulta->examesSolicitados ? 'PASSOU' : 'NÃO PASSOU' }}</p>
                <p><strong>Condição 3 (trim):</strong> {{ trim($consulta->examesSolicitados ?? '') !== '' ? 'PASSOU' : 'NÃO PASSOU' }}</p>
                <p><strong>BOTÃO DEVE APARECER?:</strong> 
                    {{ isset($consulta) && $consulta->examesSolicitados && trim($consulta->examesSolicitados) !== '' ? 'SIM' : 'NÃO' }}
                </p>
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

        {{-- BOTÃO PDF - VERSÃO FLEXÍVEL --}}
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
                
                {{-- BOTÃO SEMPRE VISÍVEL MAS CONDICIONAL --}}
                @if($consulta->examesSolicitados && trim($consulta->examesSolicitados) !== '')
                    <a href="{{ route('medico.gerarPdfExames', $consulta->idConsultaPK) }}" 
                       class="btn-pdf-generate"
                       id="pdf-btn-{{ $consulta->idConsultaPK }}">
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
                    placeholder="Liste os exames solicitados, um por linha..."
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
                    <input type="hidden" name="medicamentos_prescritos" value="">

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

            <div class="input-group">
                <label for="posologia">
                    <i class="bi bi-clock-history"></i> Posologia e Instruções de Uso
                </label>
                <textarea 
                    id="posologia" 
                    name="posologia" 
                    rows="4"
                    placeholder="Ex: Paracetamol 500mg - 1 comprimido de 8/8h por 5 dias&#10;Amoxicilina 500mg - 1 cápsula de 8/8h por 7 dias"
                >{{ old('posologia') }}</textarea>
                @error('posologia')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-group">
                <a href="{{ route('medico.prontuario') }}" class="btn-cancelar">
                    <i class="bi bi-x-circle"></i> {{ isset($consulta) ? 'Voltar para Fila' : 'Cancelar' }}
                </a>
                <button type="submit" class="save-button">
                    <i class="bi bi-check-circle"></i> {{ isset($consulta) ? 'Finalizar Atendimento' : 'Salvar Prontuário' }}
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    document.getElementById('filtroMedicamentos').addEventListener('input', function() {
        const termo = this.value.toLowerCase();
        document.querySelectorAll('#listaMedicamentos .checkbox-item').forEach(item => {
            const texto = item.textContent.toLowerCase();
            item.style.display = texto.includes(termo) ? '' : 'none';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Loading para o botão PDF
        const pdfBtns = document.querySelectorAll('[id^="pdf-btn-"]');
        
        pdfBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const originalHtml = btn.innerHTML;
                
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Gerando PDF...';
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
                
                // Restaurar após 5 segundos (fallback)
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                }, 5000);
            });
        });
    });
</script>
@endsection