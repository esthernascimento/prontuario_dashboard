@extends('medico.templates.medicoTemplate')

@section('title', 'Perfil do Médico')

@section('content')
{{-- Garanta que o caminho do CSS esteja correto: 'css/medico/perfilMedico.css' --}}
<link rel="stylesheet" href="{{ asset('css/medico/perfilMedico.css') }}">

@php $medico = auth()->user(); @endphp

<main class="main-dashboard">
  <div class="cadastrar-container">
    <div class="cadastrar-header">
      {{-- Adicione uma classe "icon" no ícone para o CSS animar --}}
      <i class="bi bi-person-circle icon"></i> 
      <h1>Perfil do Médico</h1>
    </div>

    {{-- Removido o bloco de alertas de sessão e erros para replicar o visual limpo do admin --}}

    <form action="{{ route('medico.perfil.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Bloco da Foto (Fundo Vermelho/Vinho) --}}
      <div class="foto-upload-container">
        <label for="foto" class="foto-upload-label">
          <div class="box-foto">
            <img id="preview-img"
              src="{{ $medico?->foto ? asset('storage/fotos/' . $medico->foto) : asset('img/usuario-de-perfil.png') }}"
              alt="Foto atual">
          </div>

          {{-- Overlay para Alterar Foto --}}
          <div class="overlay">
            <i class="bi bi-camera"></i>
            <span>Alterar Foto</span>
          </div>
        </label>
        <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
      </div>

      {{-- Campos de Dados --}}
      <div class="input-group">
        <input type="text" name="nomeMedico" id="nomeMedico" placeholder="Nome Completo"
               value="{{ old('nomeMedico', $medico?->nomeMedico) }}" required>
      </div>

      <div class="input-group">
        <input type="text" name="crmMedico" id="crmMedico" placeholder="CRM"
               value="{{ $medico?->crmMedico }}" disabled title="Campo não editável">
      </div>

      <div class="input-group">
        <input type="email" name="emailMedico" id="emailMedico" placeholder="E-mail"
               value="{{ old('emailMedico', $medico?->emailMedico) }}" required>
      </div>

      {{-- Botões de Ação --}}
      <div class="button-group">
        {{-- Classe e estrutura idênticas às do admin --}}
        <a href="{{ route('medico.seguranca') }}" class="btn-trocar-senha">Trocar Senha</a>
        <button type="submit" class="save-button">Salvar Alterações</button>
      </div>
    </form>
  </div>
</main>

<script>
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

{{-- Script de redirecionamento do admin (opcional, dependendo do seu fluxo de notificação) --}}
// window.onload = function() {
//   @if(session('success'))
//   setTimeout(function() {
//     window.location.href = "{{ route('medico.dashboard') }}";
//   }, 3000);
//   @endif
//
//   @if(session('error'))
//   setTimeout(function() {
//     window.location.href = "{{ route('medico.perfil') }}";
//   }, 3000);
//   @endif
// };
</script>
@endsection