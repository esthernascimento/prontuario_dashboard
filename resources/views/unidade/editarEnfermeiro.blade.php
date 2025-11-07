@extends('unidade.templates.unidadeTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/unidade/editarEnfermeiro.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-pencil-square icon"></i>
            <h1>Editar Enfermeiro(a)</h1>
        </div>

        <form id="editarEnfermeiroForm" action="{{ route('unidade.enfermeiro.update', $enfermeiro->idEnfermeiroPK) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section-title">Dados do(a) Enfermeiro(a)</div>

            <div class="input-group">
                <label for="nomeEnfermeiro">Nome do Enfermeiro</label>
                <input type="text" name="nomeEnfermeiro" id="nomeEnfermeiro" value="{{ $enfermeiro->nomeEnfermeiro }}" required>
            </div>

            <div class="input-group">
                <label for="corenEnfermeiro">COREN</label>
                <input type="text" name="corenEnfermeiro" id="corenEnfermeiro" value="{{ $enfermeiro->corenEnfermeiro }}" required>
            </div>

            <div class="split-group">
                <div class="input-group">
                    <label for="genero">Gênero</label>
                    <select name="genero" id="genero" class="custom-select" required>
                        <option value="Feminino" {{ $enfermeiro->genero == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                        <option value="Masculino" {{ $enfermeiro->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Outro" {{ $enfermeiro->genero == 'Outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="emailEnfermeiro">Email do Enfermeiro</label>
                    <input type="email" name="emailEnfermeiro" id="emailEnfermeiro" value="{{ $enfermeiro->emailEnfermeiro }}" required>
                </div>
            </div>

            <hr class="section-separator">

            <div class="form-section-title">Dados de Acesso (Login)</div>

            <div class="input-group">
                <label for="nomeUsuario">Nome do Usuário</label>
                <input type="text" name="nomeUsuario" id="nomeUsuario" value="{{ $enfermeiro->usuario->nomeUsuario }}" required>
            </div>

            <div class="input-group">
                <label for="emailUsuario">Email do Usuário</label>
                <input type="email" name="emailUsuario" id="emailUsuario" value="{{ $enfermeiro->usuario->emailUsuario }}" required>
            </div>

            <button type="button" class="save-button" onclick="openEditModal()">Salvar Alterações</button>
        </form>
    </div>
</main>

{{-- MODAL CONFIRMAÇÃO DE EDIÇÃO --}}
<div id="editEnfermeiroModal" class="modal-overlay">
    <div class="modal-content">
        <i class="bi bi-pencil-square"></i>
        <h2>Confirmar Edição</h2>
        <p>Deseja realmente salvar as alterações feitas neste(a) enfermeiro(a)?</p>
        <div class="modal-buttons">
            <button type="button" onclick="closeEditModal()" class="btn-cancelar">Cancelar</button>
            <button type="button" onclick="submitEditForm()" class="btn-excluir">Sim, salvar</button>
        </div>
    </div>
</div>


<script>
function openEditModal() {
    document.getElementById('editEnfermeiroModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editEnfermeiroModal').style.display = 'none';
}

function submitEditForm() {
    document.getElementById('editarEnfermeiroForm').submit();
}

// Fechar modal clicando fora
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if(e.target === modal) modal.style.display = 'none';
    });
});

</script>

@endsection
