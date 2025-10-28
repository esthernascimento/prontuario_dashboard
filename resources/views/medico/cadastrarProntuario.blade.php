@extends('medico.templates.medicoTemplate')

{{-- O título muda dependendo se estamos criando ou editando --}}
@section('title', isset($consulta) ? 'Finalizar Atendimento' : 'Cadastrar Consulta')

@section('content')
{{-- Usa o mesmo CSS --}}
<link rel="stylesheet" href="{{ asset('css/medico/cadastrarProntuario.css') }}">
{{-- Estilos adicionais para anotações do enfermeiro e badges (copiados da versão anterior 'editarProntuario') --}}
<style>
    .anotacoes-enfermagem-container {
        margin: 32px 36px;
        padding: 28px;
        background-color: #f0f9ff; /* Azul bem claro */
        border: 1px solid #cce5ff;
        border-left: 5px solid #0d6efd; /* Destaque azul */
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
    }
    .anotacoes-enfermagem-container h3 {
        color: #0a58ca; /* Azul escuro */
        font-size: 1.15rem;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
    }
     .anotacoes-enfermagem-container h3 i {
        font-size: 1.3rem;
    }
    .anotacao-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px dashed var(--border-color);
    }
     .anotacao-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .anotacao-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }
     .anotacao-header strong {
        font-weight: 600;
        color: var(--text-primary);
    }
    .anotacao-body p {
        margin: 5px 0;
        line-height: 1.6;
        color: var(--text-primary);
    }
     .anotacao-body strong {
        font-weight: 600;
    }
    .sinais-vitais-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px 20px;
        margin-top: 10px;
        font-size: 0.9rem;
    }
     .sinal-vital-item {
        background-color: #e6f7ff;
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid #b3e0ff;
     }

     .sinal-vital-item strong { color: #0056b3; }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 20px;
        text-transform: uppercase;
        color: white;
        min-width: 100px;
        text-align: center;
    }
    .status-vermelho { background-color: #dc3545; }
    .status-laranja { background-color: #fd7e14; }
    .status-amarelo { background-color: #ffc107; color: #000; }
    .status-verde { background-color: #198754; }
    .status-azul { background-color: #0d6efd; }
</style>

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            {{-- Ícone e Título mudam dependendo se é 'create' ou 'edit' --}}
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
                {{-- Mostra a Classificação de Risco apenas se estiver editando uma consulta existente --}}
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
            {{-- Adiciona o método PUT apenas se estiver editando --}}
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
                    {{-- Preenche com a data da consulta existente ou data atual --}}
                    value="{{ old('dataConsulta', isset($consulta) && $consulta->dataConsulta ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('Y-m-d') : date('Y-m-d')) }}"
                    required
                    class="input-date"
                >
                @error('dataConsulta')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            {{-- CAMPO UNIDADE FOI REMOVIDO --}}

            <div class="input-group">
                <label for="observacoes">
                    <i class="bi bi-file-text"></i> Observações Médicas / Diagnóstico
                </label>
                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="4"
                    placeholder="Descreva o diagnóstico, evolução e observações médicas..."
                    {{-- Preenche com dados da consulta existente, se houver --}}
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
                     {{-- Preenche com dados da consulta existente, se houver --}}
                >{{ old('examesSolicitados', $consulta->examesSolicitados ?? '') }}</textarea>
                @error('examesSolicitados')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="medicamentosPrescritos">
                    <i class="bi bi-capsule"></i> Medicamentos Prescritos
                </label>
                <textarea
                    id="medicamentosPrescritos"
                    name="medicamentosPrescritos"
                    rows="4"
                    placeholder="Liste os medicamentos prescritos com posologia..."
                     {{-- Preenche com dados da consulta existente, se houver --}}
                >{{ old('medicamentosPrescritos', $consulta->medicamentosPrescritos ?? '') }}</textarea>
                @error('medicamentosPrescritos')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-group">
                {{-- O link/texto do botão Cancelar muda --}}
                <a href="{{ route('medico.prontuario') }}" class="btn-cancelar">
                    <i class="bi bi-x-circle"></i> {{ isset($consulta) ? 'Voltar para Fila' : 'Cancelar' }}
                </a>
                {{-- O texto do botão Salvar muda --}}
                <button type="submit" class="save-button">
                    <i class="bi bi-check-circle"></i> {{ isset($consulta) ? 'Finalizar Atendimento' : 'Salvar Prontuário' }}
                </button>
            </div>
        </form>
    </div>
</main>

@endsection
