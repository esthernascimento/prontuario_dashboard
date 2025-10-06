@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/seguranca.css') }}">

<main class="main-dashboard">
    <div class="security-container">
        <h1><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
        <p>Gerencie suas configurações de segurança e privacidade.</p>

        <div class="security-section">
            <h2><i class="bi bi-key-fill"></i> Alterar Senha</h2>

            {{-- Mensagens de erro de validação --}}
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Formulário com ID e botão modificado --}}
            <form id="securityForm" action="{{ route('enfermeiro.alterarSenha') }}" method="POST" class="security-form">
                @csrf
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                    <small class="form-hint">Mínimo de 8 caracteres.</small>
                </div>
                <div class="form-group">
                    <label for="nova_senha_confirmation">Confirmar Nova Senha:</label>
                    <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" required>
                </div>
                {{-- Botão agora chama o modal de confirmação --}}
                <button type="button" class="btn-primary" onclick="showConfirmationModal()">Alterar Senha</button>
            </form>
        </div>
    </div>
</main>

{{-- ======================================================== --}}
{{-- HTML DOS MODAIS ADICIONADO AQUI                     --}}
{{-- ======================================================== --}}

<div id="confirmationModal" class="modal-overlay">
    <div class="modal-box">
        <i class="bi bi-exclamation-triangle-fill modal-icon icon-warning"></i>
        <h2>Alterar Senha</h2>
        <p>Você tem certeza que deseja alterar sua senha?</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideConfirmationModal()">Cancelar</button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitSecurityForm()">Confirmar</button>
        </div>
    </div>
</div>

@if(session('success'))
<div id="successModal" class="modal-overlay show">
    <div class="modal-box">
        <i class="bi bi-check-circle-fill modal-icon icon-success"></i>
        <h2>Sucesso!</h2>
        <p>{{ session('success') }}</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-confirm" onclick="hideSuccessModal()">Fechar</button>
        </div>
    </div>
</div>
@endif

{{-- ======================================================== --}}
{{-- JAVASCRIPT DE CONTROLE DOS MODAIS                   --}}
{{-- ======================================================== --}}
<script>
    // Pega os elementos do DOM para os modais
    const securityForm = document.getElementById('securityForm');
    const confirmationModal = document.getElementById('confirmationModal');
    const successModal = document.getElementById('successModal');

    // --- Funções para o Modal de Confirmação ---
    function showConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.add('show');
    }
    function hideConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.remove('show');
    }
    function submitSecurityForm() {
        hideConfirmationModal();
        if (securityForm) securityForm.submit();
    }

    // --- Funções para o Modal de Sucesso ---
    function hideSuccessModal() {
        if (successModal) successModal.classList.remove('show');
    }

    // Opcional: Fechar o modal clicando fora da caixa
    window.onclick = function(event) {
        if (event.target == confirmationModal) {
            hideConfirmationModal();
        }
        if (event.target == successModal) {
            hideSuccessModal();
        }
    }
</script>

@endsection