@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/perfilAdmin.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
  <div class="cadastrar-container">
    <div class="cadastrar-header">
      <i class="bi bi-person-circle icon"></i>
      <h1>Perfil do Administrador</h1>
    </div>

    <form action="{{ route('admin.perfil.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Bloco da Foto (Fundo Azul) --}}
      <div class="foto-upload-container">
        <label for="foto" class="foto-upload-label">

          <div class="box-foto">
            <img id="preview-img"
              src="{{ $admin->foto ? asset('storage/fotos/' . $admin->foto) : asset('img/usuario-de-perfil.png') }}"
              alt="Foto atual">
          </div>

          {{-- Texto e Ícone Centralizados na imagem --}}
          <div class="overlay">
            <i class="bi bi-camera"></i>
            <span>Alterar Foto</span>
          </div>
        </label>
        <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
      </div>

      {{-- Campos de Dados --}}
      <div class="input-group">
        {{-- Removendo a label se você quer o design da imagem --}}
        <input type="text" name="nomeAdmin" id="nomeAdmin" value="{{ $admin->nomeAdmin }}" required>
      </div>

      <div class="input-group">
        <input type="email" name="emailAdmin" id="emailAdmin" value="{{ $admin->emailAdmin }}" required>
      </div>

      {{-- Botões de Ação --}}
      <div class="button-group">
        <a href="{{ route('admin.seguranca') }}" class="btn-trocar-senha">Trocar Senha</a> <button type="submit" class="save-button">Salvar Alterações</button>
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
      window.location.href = "{{ route('admin.dashboard') }}";
    }, 3000);
    @endif

    @if(session('error'))
    setTimeout(function() {
      window.location.href = "{{ route('admin.perfil') }}";
    }, 3000);
    @endif
  };
</script>
@endsection