@extends('medico.templates.medicoTemplate')

@section('title', 'Cadastrar Prontuário')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/cadastrarProntuario.css') }}">

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-journal-medical icon"></i>
            <h1>Cadastrar Consulta ao Prontuário</h1>
        </div>

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
            </div>
        </div>

        <form action="{{ route('medico.prontuario.store', $paciente->idPaciente) }}" method="POST" id="prontuarioForm">
            @csrf

            <div class="form-section-title">Dados da Consulta</div>

            <div class="input-group">
                <label for="dataConsulta">
                    <i class="bi bi-calendar-check"></i> Data da Consulta *
                </label>
                <input 
                    type="date" 
                    id="dataConsulta" 
                    name="dataConsulta" 
                    value="{{ old('dataConsulta', date('Y-m-d')) }}"
                    required
                    class="input-date"
                >
                @error('dataConsulta')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="unidade">
                    <i class="bi bi-hospital"></i> Unidade da Consulta Realizada
                </label>
                <input 
                    type="text" 
                    id="unidade" 
                    name="unidade" 
                    value="{{ old('unidade') }}"
                    placeholder="Ex: Unidade Básica de Saúde Central"
                    class="input-text"
                >
                @error('unidade')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="observacoes">
                    <i class="bi bi-file-text"></i> Observações
                </label>
                <textarea 
                    id="observacoes" 
                    name="observacoes" 
                    rows="4"
                    placeholder="Descreva observações gerais sobre a consulta..."
                >{{ old('observacoes') }}</textarea>
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
                >{{ old('examesSolicitados') }}</textarea>
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
                >{{ old('medicamentosPrescritos') }}</textarea>
                @error('medicamentosPrescritos')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-group">
                <a href="{{ route('medico.prontuario') }}" class="btn-cancelar">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="save-button">
                    <i class="bi bi-check-circle"></i> Salvar Prontuário
                </button>
            </div>
        </form>
    </div>
</main>

@endsection