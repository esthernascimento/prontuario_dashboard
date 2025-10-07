@extends('medico.templates.medicoTemplate')

@section('title', 'Cadastrar Prontuário')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/cadastrarProntuario.css') }}">

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-journal-medical icon"></i>
            <h1>Cadastrar Consulta ao Prontuário+</h1>
        </div>

        <div class="paciente-info">
            <h3><i class="bi bi-person-fill"></i> Paciente: {{ $paciente->nomePaciente }}</h3>
            <p><strong>CPF:</strong> {{ $paciente->cpfPaciente }}</p>
            <p><strong>Data de Nascimento:</strong> {{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') }}</p>
        </div>

        <form action="{{ route('medico.prontuario.store', $paciente->idPaciente) }}" method="POST" id="prontuarioForm">
            @csrf

            <div class="form-section-title">Dados da Consulta</div>

            <div class="input-group">
                <label for="queixaPrincipal">Queixa Principal *</label>
                <textarea id="queixaPrincipal" name="queixaPrincipal" rows="3" required></textarea>
                @error('queixaPrincipal')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="historiaDoenca">História da Doença Atual</label>
                <textarea id="historiaDoenca" name="historiaDoenca" rows="4"></textarea>
            </div>

            <div class="input-group">
                <label for="examesFisicos">Exames Físicos</label>
                <textarea id="examesFisicos" name="examesFisicos" rows="4"></textarea>
            </div>

            <div class="split-group">
                <div class="input-group">
                    <label for="diagnostico">Diagnóstico</label>
                    <textarea id="diagnostico" name="diagnostico" rows="3"></textarea>
                </div>

                <div class="input-group">
                    <label for="tratamento">Tratamento Proposto</label>
                    <textarea id="tratamento" name="tratamento" rows="3"></textarea>
                </div>
            </div>

            <div class="input-group">
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="3"></textarea>
            </div>

            <div class="button-group">
                <a href="{{ route('medico.prontuario') }}" class="btn-cancelar">Cancelar</a>
                <button type="submit" class="save-button">Salvar Prontuário</button>
            </div>
        </form>
    </div>
</main>

@endsection