@extends('unidade.templates.unidadeTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/unidade/perfilUnidade.css') }}">

@php $unidade = auth()->guard('unidade')->user(); @endphp

<main class="main-dashboard">
  <div class="cadastrar-container">
    <div class="cadastrar-header">
      <i class="bi bi-person-circle icon"></i>
      <h1>Perfil da Unidade</h1>
    </div>

    <form action="{{ route('unidade.perfil.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      {{-- Bloco da Foto (Fundo Azul) --}}
      <div class="foto-upload-container">
        <label for="foto" class="foto-upload-label">

          <div class="box-foto">
            <img id="preview-img"
              src="{{ $unidade->usuario && $unidade->usuario->foto ? asset('storage/fotos/'.$unidade->usuario->foto) : '' }}"
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
        <input type="text" name="nomeUnidade" id="nomeUnidade" value="{{ $unidade->nomeUnidade }}" required>
      </div>

      <div class="input-group">
        <input type="email" name="emailUnidade" id="emailUnidade" value="{{ $unidade->emailUnidade }}" required>
      </div>

      <div class="button-group">
        <a href="{{ route('unidade.seguranca') }}" class="btn-trocar-senha">Trocar Senha</a>
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

  window.onload = function() {
    @if(session('success'))
    setTimeout(function() {
      window.location.href = "{{ route('unidade.dashboard') }}";
    }, 3000);
    @endif

    @if(session('error'))
    setTimeout(function() {
      window.location.href = "{{ route('unidade.perfil') }}";
    }, 3000);
    @endif
  };
</script>
@endsection