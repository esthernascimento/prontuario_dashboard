@extends('admin.templates.admTemplate')

@section('title', 'Editar Paciente')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/editarPaciente.css') }}">

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-pencil-square icon"></i>
            <h1>Editar Paciente</h1>
        </div>


        <form action="{{ route('admin.pacientes.update', $paciente->idPaciente) }}" method="POST" id="editPacienteForm">
            @csrf
            @method('PUT')

            <div class="form-section-title">Dados do Paciente</div>

            <div class="input-group">
                <label for="nomePaciente">Nome Completo</label>
                <input type="text" id="nomePaciente" name="nomePaciente" value="{{ $paciente->nomePaciente }}" required>
            </div>

            <div class="input-group">
                <label for="cpfPaciente">CPF</label>
                <input type="text" id="cpfPaciente" name="cpfPaciente" value="{{ $paciente->cpfPaciente }}" required>
            </div>

            <div class="input-group">
                <label for="dataNascPaciente">Data de Nascimento</label>
                <input type="date" id="dataNascPaciente" name="dataNascPaciente" value="{{ $paciente->dataNascPaciente }}" required>
            </div>

            <div class="split-group">
                <div class="input-group">
                    <label for="generoPaciente">Gênero</label>
                    <select id="generoPaciente" name="generoPaciente" required>
                        <option value="">Selecione</option>
                        <option value="Masculino" {{ $paciente->generoPaciente == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Feminino" {{ $paciente->generoPaciente == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                        <option value="Outro" {{ $paciente->generoPaciente == 'Outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="cartaoSusPaciente">Cartão SUS</label>
                    <input type="text" id="cartaoSusPaciente" name="cartaoSusPaciente" value="{{ $paciente->cartaoSusPaciente }}">
                </div>
            </div>

            <div class="input-group">
                <label for="statusPaciente">Status</label>
                <select id="statusPaciente" name="statusPaciente">
                    <option value="1" {{ $paciente->statusPaciente ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ !$paciente->statusPaciente ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <button type="button" onclick="openEditModal()" class="save-button">Salvar Alterações</button>
        </form>
    </div>
</main>

{{-- MODAL CONFIRMAÇÃO --}}
<div id="editPacienteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-pencil-square"></i>
            <h2>Confirmar Edição</h2>
        </div>
        <p>Deseja realmente salvar as alterações feitas neste(a) paciente?</p>
        <div class="modal-buttons">
            <button type="button" onclick="closeEditModal()" class="btn-cancelar">Cancelar</button>
            <button type="button" onclick="submitEditForm()" class="btn-excluir">Sim, salvar</button>
        </div>
    </div>
</div>

<script>
    function openEditModal() {
        document.getElementById('editPacienteModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editPacienteModal').style.display = 'none';
    }

    function submitEditForm() {
        document.getElementById('editPacienteForm').submit();
    }
    document.getElementById('editPacienteModal').addEventListener('click', e => {
        if (e.target.id === 'editPacienteModal') closeEditModal();
    });
</script>
@endsection