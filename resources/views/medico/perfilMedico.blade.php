@extends('medico.templates.medicoTemplate')

@section('title', 'Perfil do Médico')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/perfilMedico.css') }}">

@php $medico = auth()->user(); @endphp

<main class="main-dashboard">
  <div class="cadastrar-container">
    <div class="cadastrar-header">
      <i class="bi bi-person-circle icon"></i>
      <h1>Perfil do Médico</h1>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
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

    <form action="{{ route('medico.perfil.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="foto-upload-container">
        <label for="foto" class="foto-upload-label">
          <div class="box-foto">
            <img id="preview-img"
              src="{{ $medico?->foto ? asset('storage/fotos/' . $medico->foto) : asset('img/usuario-de-perfil.png') }}"
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
        <input type="text" name="nomeMedico" placeholder="Nome Completo"
               value="{{ old('nomeMedico', $medico?->nomeMedico) }}" required>
      </div>

      <div class="input-group">
        <input type="text" name="crmMedico" placeholder="CRM"
               value="{{ $medico?->crmMedico }}" disabled title="Campo não editável">
      </div>

      <div class="input-group">
        <input type="email" name="emailMedico" placeholder="E-mail"
               value="{{ old('emailMedico', $medico?->emailMedico) }}" required>
      </div>

      <div class="button-group">
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
</script>
@endsection
