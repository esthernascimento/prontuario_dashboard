@extends('enfermeiro.templates.enfermeiroTemplate')

@section('title', 'Registrar Triagem')

@section('content')

<body>
{{-- O CSS está sendo referenciado aqui e deve estar na pasta 'public/css/enfermeiro/cadastrarProntuario.css' --}}
<link rel="stylesheet" href="{{ asset('css/enfermeiro/cadastrarProntuario.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> 

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-journal-plus icon"></i>
            {{-- Título atualizado para refletir a ação --}}
            <h1>Realizar Triagem e Anotação</h1>
        </div>

        <div class="paciente-info">
            <h3><i class="bi bi-person-fill"></i> Informações do Atendimento</h3>
            
            <div class="info-grid">
                {{-- Variável $paciente (Passada pelo Controller) --}}
                <div class="info-item">
                    <strong>Paciente:</strong>
                    <span>{{ $paciente->nomePaciente }}</span>
                </div>
                <div class="info-item">
                    <strong>CPF:</strong>
                    <span>{{ $paciente->cpfPaciente }}</span>
                </div>
                <div class="info-item-nasc">
                    <strong>Data de Nascimento:</strong>
                    <span>{{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') }}</span>
                </div>

                {{-- Variável $enfermeiro (Passada pelo Controller) --}}
                {{-- [NOTA: O Controller precisa passar a variável $enfermeiro no método 'create'] --}}
                <div class="info-item">
                    <strong>Enfermeiro(a) Responsável:</strong>
                    <span>{{ $enfermeiro->nomeEnfermeiro ?? (Auth::user()->name ?? 'N/A') }}</span>
                </div>
                <div class="info-item">
                    <strong>COREN:</strong>
                    <span>{{ $enfermeiro->corenEnfermeiro ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <strong>Função:</strong>
                    <span>Enfermeiro(a)</span>
                </div>

                <!-- ================================================================ -->
                <!-- --- ADICIONADO: QUEIXA PRINCIPAL DO RECEPCIONISTA --- -->
                <!-- ================================================================ -->
                {{-- A variável $consulta vem do ProntuarioController@create --}}
                <div class="info-item queixa-principal">
                    <strong>Queixa Principal (Registrada na Recepção):</strong>
                    <span>"{{ $consulta->queixa_principal }}"</span>
                </div>

            </div>
        </div>

        {{-- Rota de POST: enfermeiro.anotacao.store (Definida no web.php) --}}
        <form action="{{ route('enfermeiro.anotacao.store', $paciente->idPaciente) }}" method="POST">
            @csrf

            <!-- ================================================================ -->
            <!-- --- ADICIONADO: ID DA CONSULTA (OBRIGATÓRIO) --- -->
            <!-- ================================================================ -->
            {{-- Campo oculto para enviar o ID da consulta que está sendo atualizada --}}
            <input type="hidden" name="idConsulta" value="{{ $consulta->idConsultaPK }}">

            {{-- CORREÇÃO DE ERRO: Campo tipo_registro é REQUIRED no Controller mas não existia no form --}}
            <input type="hidden" name="tipo_registro" value="Anotação de Triagem"> 

            <!-- ================================================================ -->
            <!-- --- ADICIONADO: CLASSIFICAÇÃO DE RISCO --- -->
            <!-- ================================================================ -->
            <div class="form-section-title">Classificação de Risco (Obrigatório)</div>
            <div class="input-group">
                <div id="botoes_risco">
                    <input type="radio" name="classificacao_risco" id="risco_vermelho" value="vermelho" class="btn-check" required>
                    <label class="btn btn-vermelho" for="risco_vermelho">VERMELHO (Emergência)</label>
                    
                    <input type="radio" name="classificacao_risco" id="risco_laranja" value="laranja" class="btn-check">
                    <label class="btn btn-laranja" for="risco_laranja">LARANJA (Muito Urgente)</label>
                    
                    <input type="radio" name="classificacao_risco" id="risco_amarelo" value="amarelo" class="btn-check">
                    <label class="btn btn-amarelo" for="risco_amarelo">AMARELO (Urgente)</label>
                    
                    <input type="radio" name="classificacao_risco" id="risco_verde" value="verde" class="btn-check">
                    <label class="btn btn-verde" for="risco_verde">VERDE (Pouco Urgente)</label>
                    
                    <input type="radio" name="classificacao_risco" id="risco_azul" value="azul" class="btn-check">
                    <label class="btn btn-azul" for="risco_azul">AZUL (Não Urgente)</label>
                </div>
            </div>

            <div class="form-section-title">Dados da Anotação</div>

            {{-- Campo Data e Hora --}}
            <div class="input-group">
                <label for="data_hora">
                    <i class="bi bi-calendar-check"></i> Data e Hora do Registro *
                </label>
                <input 
                    type="datetime-local" 
                    id="data_hora" 
                    name="data_hora" 
                    value="{{ old('data_hora', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                    required
                    class="input-datetime"
                >
                @error('data_hora')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-section-title">Sinais Vitais (Opcional)</div>
            
            {{-- Linha 1 de Sinais Vitais em grid de 3 colunas --}}
            <div class="input-group-row-3">
                <div class="input-group">
                    <label for="pressao_arterial">
                        <i class="bi bi-heart-pulse"></i> Pressão Arterial (mmHg)
                    </label>
                    <input type="text" id="pressao_arterial" name="pressao_arterial" value="{{ old('pressao_arterial') }}" placeholder="Ex: 120/80">
                </div>
                <div class="input-group">
                    <label for="temperatura">
                        <i class="bi bi-thermometer-half"></i> Temperatura (°C)
                    </label>
                    <input type="text" id="temperatura" name="temperatura" value="{{ old('temperatura') }}" placeholder="Ex: 36.5">
                </div>
                <div class="input-group">
                    <label for="frequencia_cardiaca">
                        <i class="bi bi-activity"></i> Freq. Cardíaca (bpm)
                    </label>
                    <input type="text" id="frequencia_cardiaca" name="frequencia_cardiaca" value="{{ old('frequencia_cardiaca') }}" placeholder="Ex: 75">
                </div>
            </div>
            
            {{-- Linha 2 de Sinais Vitais em grid de 3 colunas --}}
            <div class="input-group-row-3">
                <div class="input-group">
                    <label for="frequencia_respiratoria">
                        <i class="bi bi-lungs"></i> Freq. Respiratória (rpm)
                    </label>
                    <input type="text" id="frequencia_respiratoria" name="frequencia_respiratoria" value="{{ old('frequencia_respiratoria') }}" placeholder="Ex: 18">
                </div>
                <div class="input-group">
                    <label for="saturacao">
                        <i class="bi bi-droplet-half"></i> Saturação (SpO₂)
                    </label>
                    <input type="text" id="saturacao" name="saturacao" value="{{ old('saturacao') }}" placeholder="Ex: 98%">
                </div>
                <div class="input-group">
                    <label for="dor">
                        <i class="bi bi-bandaid"></i> Escala de Dor (0-10)
                    </label>
                    <input type="number" id="dor" name="dor" value="{{ old('dor') }}" min="0" max="10" placeholder="Ex: 5">
                </div>
            </div>

            <div class="form-section-title">Detalhes da Ocorrência</div>

            {{-- Campo Alergias --}}
            <div class="input-group">
                <label for="alergias">
                    <i class="bi bi-exclamation-triangle-fill"></i> Alergias Identificadas (Medicamentos, Alimentos, etc.)
                </label>
                <textarea 
                    id="alergias" 
                    name="alergias" 
                    rows="2"
                    placeholder="Liste todas as alergias do paciente."
                >{{ old('alergias', '') }}</textarea>
                @error('alergias')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            {{-- Campo Medicações/Procedimentos --}}
            <div class="input-group">
                <label for="medicacoes_ministradas">
                    <i class="bi bi-capsule"></i> Medicações e/ou Procedimentos Ministrados (Ações de Enfermagem)
                </label>
                <textarea 
                    id="medicacoes_ministradas" 
                    name="medicacoes_ministradas" 
                    rows="3"
                    placeholder="Descreva as medicações administradas ou procedimentos realizados."
                >{{ old('medicacoes_ministradas') }}</textarea>
                @error('medicacoes_ministradas')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            {{-- Campo Descrição/Evolução --}}
            <div class="input-group">
                <label for="descricao">
                    <i class="bi bi-file-text"></i> Descrição da Anotação / Evolução *
                </label>
                <textarea 
                    id="descricao" 
                    name="descricao" 
                    rows="8"
                    placeholder="Descreva detalhadamente a ocorrência, evolução do paciente e quaisquer observações relevantes."
                    required
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            {{-- CAMPO DE SELEÇÃO DE UNIDADE AJUSTADO --}}
            <div class="input-group">
                <label for="unidade_atendimento">
                    <i class="bi bi-hospital"></i> Unidade de Atendimento *
                </label>
                <select id="unidade_atendimento" name="unidade_atendimento" class="input-select" required>
                    <option value="" disabled selected>Selecione a unidade</option>
                    {{-- Percorre a coleção de unidades e cria uma opção para cada uma --}}
                    @foreach($unidades as $unidade)
                        <option value="{{ $unidade->idUnidadePK }}" {{ old('unidade_atendimento') == $unidade->idUnidadePK ? 'selected' : '' }}>
                            {{ $unidade->nomeUnidade }}
                        </option>
                    @endforeach
                </select>
                @error('unidade_atendimento')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-group">
                {{-- Rota de Cancelar: enfermeiro.prontuario.index (Definida no web.php) --}}
                <a href="{{ route('enfermeiro.prontuario') }}" class="btn-cancelar"> 
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="save-button">
                    <i class="bi bi-check-circle"></i> Salvar Triagem e Encaminhar
                </button>
            </div>
        </form>
    </div>
</main>
</body>
@endsection
