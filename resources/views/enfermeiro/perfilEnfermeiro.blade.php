@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/perfilEnfermeiro.css') }}">

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-person-circle icon"></i>
            <h1>Perfil do Enfermeiro</h1>
        </div>

        {{-- As mensagens de erro continuam aqui --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($enfermeiro)
            {{-- Adicionado um ID ao formulário para o JavaScript poder encontrá-lo --}}
            <form id="profileForm" action="{{ route('enfermeiro.perfil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="foto-upload-container">
                    <label for="foto" class="foto-upload-label">
                        <div class="box-foto">
                            <img id="preview-img"
                                 src="{{ $enfermeiro->foto ? asset('storage/fotos/' . $enfermeiro->foto) : asset('img/usuario-de-perfil.png') }}"
                                 alt="Foto atual">
                        </div>
                        <div class="overlay">
                            <i class="bi bi-camera"></i>
                            <span>Alterar Foto</span>
                        </div>
                    </label>
                    <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
                </div>

                <div class="input-group">
                    <input type="text" name="nomeEnfermeiro" id="nomeEnfermeiro" placeholder="Nome Completo"
                           value="{{ old('nomeEnfermeiro', $enfermeiro->nomeEnfermeiro) }}" required>
                </div>

                <div class="input-group">
                    <input type="text" name="corenEnfermeiro" id="corenEnfermeiro" placeholder="COREN/COREM"
                           value="{{ $enfermeiro->corenEnfermeiro }}"
                           disabled
                           title="Campo de identificação profissional não editável">
                </div>

                <div class="input-group">
                    <input type="email" name="emailEnfermeiro" id="emailEnfermeiro" placeholder="E-mail"
                           value="{{ old('emailEnfermeiro', $enfermeiro->emailEnfermeiro) }}" required>
                </div>

                <div class="button-group">
                    <a href="{{ route('enfermeiro.seguranca') }}" class="btn-trocar-senha">Trocar Senha</a>
                    {{-- Botão alterado: type="button" para não enviar o form, e chama a função JS --}}
                    <button type="button" class="save-button" onclick="showConfirmationModal()">Salvar Alterações</button>
                </div>
            </form>
        @else
            <div class="alert alert-danger text-center">
                <p><strong>Erro!</strong> Não foi possível carregar os dados do perfil.</p>
                <p>Por favor, tente fazer o <a href="{{ route('enfermeiro.login') }}">login</a> novamente.</p>
            </div>
        @endif
    </div>
</main>

{{-- ======================================================== --}}
{{-- HTML DOS MODAIS ADICIONADO AQUI                     --}}
{{-- ======================================================== --}}

<div id="confirmationModal" class="modal-overlay">
    <div class="modal-box">
        <i class="bi bi-exclamation-triangle-fill modal-icon icon-warning"></i>
        <h2>Confirmar Alterações</h2>
        <p>Deseja realmente salvar as alterações no seu perfil?</p>
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideConfirmationModal()">Cancelar</button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitProfileForm()">Confirmar</button>
        </div>
    </div>
</div>

@if(session('success'))
<div id="successModal" class="modal-overlay show"> {{-- A classe 'show' faz ele aparecer --}}
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
{{-- JAVASCRIPT DE CONTROLE (FOTO E MODAIS)              --}}
{{-- ======================================================== --}}
<script>
    // Função para preview da imagem
    function previewFoto(event) {
        const input = event.target;
        const preview = document.getElementById('preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Pega os elementos do DOM para os modais
    const profileForm = document.getElementById('profileForm');
    const confirmationModal = document.getElementById('confirmationModal');
    const successModal = document.getElementById('successModal');

    // --- Funções para o Modal de Confirmação ---
    function showConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.add('show');
    }
    function hideConfirmationModal() {
        if (confirmationModal) confirmationModal.classList.remove('show');
    }
    function submitProfileForm() {
        hideConfirmationModal();
        if (profileForm) profileForm.submit();
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