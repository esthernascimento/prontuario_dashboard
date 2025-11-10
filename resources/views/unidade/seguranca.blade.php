@extends('unidade.templates.unidadeTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/unidade/UnidadeSeguranca.css') }}">

<!-- @php $unidade = auth()->guard('unidade')->user(); @endphp -->

<main class="main-dashboard">
    <div class="security-container">
        <h1 class="main-title"><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
        <p class="main-subtitle">Gerencie suas configurações de segurança e privacidade</p>

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

            <form action="{{ route('unidade.alterarSenha') }}" method="POST" class="security-form" id="securityForm">
                @csrf
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <div class="password-input-container">
                        <input type="password" id="senha_atual" name="senha_atual" required>
                        <i class="bi bi-eye-fill toggle-password" data-target="senha_atual"></i>
                    </div>
                    @error('senha_atual')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <div class="password-input-container">
                        <input type="password" id="nova_senha" name="nova_senha" required>
                        <i class="bi bi-eye-fill toggle-password" data-target="nova_senha"></i>
                    </div>
                    <small class="form-hint">Mínimo de 8 caracteres, incluindo números e letras</small>
                    @error('nova_senha')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nova_senha_confirmation">Confirmar Nova Senha:</label>
                    <div class="password-input-container">
                        <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" required>
                        <i class="bi bi-eye-fill toggle-password" data-target="nova_senha_confirmation"></i>
                    </div>
                </div>

                <button type="button" id="openConfirmationModal" class="btn-primary">Alterar Senha</button>
            </form>
        </div>

    </div>
</main>

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

<script>
    const form = document.getElementById('securityForm'); 
    const openConfirmationModalButton = document.getElementById('openConfirmationModal');
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmChangePasswordButton = document.getElementById('confirmChangePassword');
    const successModal = document.getElementById('successModal');

    // Funções para mostrar/ocultar senha
    function setupPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this;
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye-fill');
                    icon.classList.add('bi-eye-slash-fill');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash-fill');
                    icon.classList.add('bi-eye-fill');
                }
            });
        });
    }

    // Funções dos modais
    function showConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.add('show');
    }

    function hideConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.remove('show');
    }

    function hideSuccessModal() {
        if (successModal) {
            successModal.classList.remove('show');
            // Redireciona para o perfil após fechar o modal de sucesso
            setTimeout(() => {
                window.location.href = "{{ route('unidade.perfil') }}";
            }, 300);
        }
    }

    if (openConfirmationModalButton && form) {
        openConfirmationModalButton.addEventListener('click', (event) => {
            if (form.checkValidity()) {
                showConfirmationModal();
            } else {
                form.reportValidity();
            }
        });
    }

    if (confirmChangePasswordButton && form) {
        confirmChangePasswordButton.addEventListener('click', () => {
            hideConfirmationModal();
            form.submit(); 
        });
    }

    window.onclick = function(event) {
        if (event.target === confirmationModal) {
            hideConfirmationModal();
        }
        if (event.target === successModal) {
            hideSuccessModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideConfirmationModal();
            hideSuccessModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordToggle();
    });
</script>

@endsection