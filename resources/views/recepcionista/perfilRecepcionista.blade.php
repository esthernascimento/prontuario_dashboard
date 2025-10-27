@extends('layouts.recepcionista')

@section('content')

<link rel="stylesheet" href="{{ url('/css/recepcionista/perfilRecepcionista.css') }}">

<div class="cadastrar-container">
    <div class="cadastrar-header">
        <i class="bi bi-person-fill"></i>
        <h1>Meu Perfil (Recepcionista)</h1>
    </div>

    <form action="{{ route('recepcionista.atualizarPerfil') }}" method="POST">
        @csrf
        
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <div class="input-group">
            <label for="nomeRecepcionista">Nome:</label>
            <input type="text" name="nomeRecepcionista" id="nomeRecepcionista" value="{{ old('nomeRecepcionista', $recepcionista->nomeRecepcionista) }}" required>
        </div>

        <div class="input-group">
            <label for="emailRecepcionista">E-mail:</label>
            <input type="email" name="emailRecepcionista" id="emailRecepcionista" value="{{ old('emailRecepcionista', $recepcionista->emailRecepcionista) }}" required>
        </div>

        <div class="button-group">
            <button type="submit" class="save-button">
                Salvar Alterações
            </button>
            
            <button type="button" id="openModalBtn" class="btn-trocar-senha">
                <i class="bi bi-key-fill"></i> Trocar Senha
            </button>
        </div>
    </form>
</div>

{{-- ESTRUTURA DO MODAL (Deve estar fora do form principal) --}}
<div id="passwordModal" class="modal-overlay">
    <div class="modal-box">
        <i class="bi bi-shield-lock-fill modal-icon icon-warning"></i>
        <h2>Confirmação de Senha</h2>
        <p>Para sua segurança, confirme a senha atual antes de definir uma nova.</p>

        <form id="trocaSenhaForm" action="{{ route('recepcionista.trocarSenha') }}" method="POST">
            @csrf
            
            <div class="input-group">
                <label for="current_password">Senha Atual:</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>

            <div class="input-group">
                <label for="new_password">Nova Senha:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            
            <div class="input-group">
                <label for="new_password_confirmation">Confirmar Nova Senha:</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
            </div>

            <div class="modal-buttons">
                <button type="button" id="closeModalBtn" class="modal-btn modal-btn-cancel">
                    Cancelar
                </button>
                <button type="submit" class="modal-btn modal-btn-confirm">
                    Confirmar Troca
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('passwordModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    openBtn.addEventListener('click', () => {
        modal.classList.add('show');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});
</script>
@endsection