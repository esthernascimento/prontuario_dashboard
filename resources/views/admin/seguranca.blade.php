@extends('admin.templates.admTemplate')

@section('content')

{{-- Garante que o CSS está linkado --}}
<link rel="stylesheet" href="{{ asset('css/admin/seguranca.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
    <div class="security-container">
        <h1 class="main-title"><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
        <p class="main-subtitle">Gerencie suas configurações de segurança e privacidade</p>

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
            <form action="{{ route('admin.alterarSenha') }}" method="POST" class="security-form" id="securityForm">
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
{{-- 1. HTML DOS MODAIS (AGORA INCLUÍDO CORRETAMENTE) --}}
{{-- ============================================ --}}

{{-- MODAL DE CONFIRMAÇÃO (Alerta Amarelo/Laranja) --}}
<div id="confirmationModal" class="modal-overlay">
    <div class="modal-box">
        <i class="bi bi-exclamation-triangle-fill modal-icon icon-warning"></i>
        <h2>Alterar Senha</h2>
        <p>Você tem certeza que deseja alterar sua senha? Esta ação é irreversível.</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideConfirmationModal()">Cancelar</button>
            <button type="button" class="modal-btn modal-btn-confirm" id="confirmChangePassword">Confirmar Alteração</button>
        </div>
    </div>
</div>

{{-- MODAL DE SUCESSO (Alerta Verde) --}}
@if(session('success'))
<div id="successModal" class="modal-overlay show">
    <div class="modal-box">
        <i class="bi bi-check-circle-fill modal-icon icon-success"></i>
        <h2>Senha Alterada!</h2>
        <p>{{ session('success') }}</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-confirm" onclick="hideSuccessModal()">Fechar</button>
        </div>
    </div>
</div>
@endif


{{-- ============================================ --}}
{{-- 2. SCRIPT PARA CONTROLE DOS MODAIS (CORRIGIDO) --}}
{{-- ============================================ --}}
<script>
    // Referências aos elementos
    const form = document.getElementById('securityForm'); // Referência ao FORM com o novo ID
    const openConfirmationModalButton = document.getElementById('openConfirmationModal');
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmChangePasswordButton = document.getElementById('confirmChangePassword');
    const successModal = document.getElementById('successModal');

    // Funções para o Modal de Confirmação
    function showConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.add('show');
    }

    function hideConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.remove('show');
    }

    function hideSuccessModal() {
        if (successModal) successModal.classList.remove('show');
    }

    // 1. Liga o botão "Alterar Senha" para mostrar o modal de confirmação
    if (openConfirmationModalButton) {
        openConfirmationModalButton.addEventListener('click', (event) => {
            // Verifica se o formulário é válido (campos 'required' preenchidos)
            if (form.checkValidity()) {
                showConfirmationModal();
            } else {
                // Se não for válido, dispara a validação padrão do navegador
                form.reportValidity();
            }
        });
    }

    // 2. Liga o botão de Confirmação do modal para realmente enviar o formulário
    if (confirmChangePasswordButton) {
        confirmChangePasswordButton.addEventListener('click', () => {
            hideConfirmationModal();
            form.submit(); // Envia o formulário
        });
    }

    // 3. Lógica para fechar os modais ao clicar fora
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