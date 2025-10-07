@extends('medico.templates.medicoTemplate')

@section('title', 'Segurança')

@section('content')
{{-- Usa o CSS específico para o Médico --}}
<link rel="stylesheet" href="{{ asset('css/medico/MedicoSeguranca.css') }}">

<main class="main-dashboard">
    <div class="security-container">
        <h1><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
        <p>Gerencie suas configurações de segurança e privacidade</p>

        {{-- Mensagens de erro GLOBAIS (apenas erro) --}}
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="security-section">
            <h2><i class="bi bi-key-fill"></i> Alterar Senha</h2>

            {{-- O ID 'securityForm' foi adicionado para o JavaScript --}}
            <form action="{{ route('medico.alterarSenha') }}" method="POST" class="security-form" id="securityForm">
                @csrf
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                    @error('senha_atual')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                    <small class="form-hint">Mínimo de 8 caracteres, incluindo números e letras</small>
                    @error('nova_senha')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nova_senha_confirmation">Confirmar Nova Senha:</label>
                    <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" required>
                </div>
                
                {{-- ALTERADO: type="submit" para type="button" e adicionado ID para JS --}}
                <button type="button" id="openConfirmationModal" class="btn-primary">Alterar Senha</button>
            </form>
        </div>

    </div>
</main>

{{-- ============================================ --}}
{{-- 1. HTML DOS MODAIS (BASEADO NO DO ADMINISTRADOR) --}}
{{-- ============================================ --}}

{{-- MODAL DE CONFIRMAÇÃO (Alerta Amarelo/Laranja) --}}
<div id="confirmationModal" class="modal-overlay">
    <div class="modal-box">
        <i class="bi bi-exclamation-triangle-fill modal-icon icon-warning"></i>
        <h2>Alterar Senha</h2>
        <p>Você tem certeza que deseja alterar sua senha? Esta ação é irreversível.</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideConfirmationModal()">Cancelar</button>
            {{-- O botão de Confirmação usa a classe .modal-btn-confirm-medico para aplicar a cor do tema --}}
            <button type="button" class="modal-btn modal-btn-confirm modal-btn-confirm-medico" id="confirmChangePassword">Confirmar Alteração</button>
        </div>
    </div>
</div>

{{-- MODAL DE SUCESSO (Aparece se houver session('success')) --}}
@if(session('success'))
<div id="successModal" class="modal-overlay show">
    <div class="modal-box">
        {{-- O ícone de sucesso usará a cor do tema Médico (Vermelho) no CSS --}}
        <i class="bi bi-check-circle-fill modal-icon icon-success-medico"></i>
        <h2>Senha Alterada!</h2>
        <p>{{ session('success') }}</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-confirm modal-btn-confirm-medico" onclick="hideSuccessModal()">Fechar</button>
        </div>
    </div>
</div>
@endif


{{-- ============================================ --}}
{{-- 2. SCRIPT PARA CONTROLE DOS MODAIS (BASEADO NO DO ADMINISTRADOR) --}}
{{-- ============================================ --}}
<script>
    // Referências aos elementos
    const form = document.getElementById('securityForm');
    const openConfirmationModalButton = document.getElementById('openConfirmationModal');
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmChangePasswordButton = document.getElementById('confirmChangePassword');
    const successModal = document.getElementById('successModal');

    // Funções para mostrar/esconder modais
    function showConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.add('show');
    }

    function hideConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.remove('show');
    }

    function hideSuccessModal() {
        if (successModal) successModal.classList.remove('show');
        // Opcional: Recarregar a página ou limpar a URL após fechar o modal de sucesso.
        // history.replaceState(null, '', location.pathname); 
    }

    // 1. Liga o botão "Alterar Senha" para mostrar o modal de confirmação
    if (openConfirmationModalButton && form) {
        openConfirmationModalButton.addEventListener('click', (event) => {
            // Se o formulário for inválido, o navegador trata (mostra os balões de erro required)
            if (form.checkValidity()) {
                showConfirmationModal();
            } else {
                // Força o navegador a mostrar as mensagens de validação HTML5
                form.reportValidity();
            }
        });
    }

    // 2. Liga o botão de Confirmação do modal para realmente enviar o formulário
    if (confirmChangePasswordButton && form) {
        confirmChangePasswordButton.addEventListener('click', () => {
            hideConfirmationModal();
            form.submit(); // Envia o formulário
        });
    }

    // 3. Lógica para fechar os modais ao clicar fora
    window.onclick = function(event) {
        if (event.target === confirmationModal) {
            hideConfirmationModal();
        }
        if (event.target === successModal) {
            hideSuccessModal();
        }
    }
</script>

@endsection