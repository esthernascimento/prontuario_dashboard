@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/editarMedico.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
  <div class="cadastrar-container">
    <div class="cadastrar-header">
      <i class="bi bi-pencil-square"></i>
      <h1>Editar Enfermeiro(a)</h1>
    </div>

    <form action="{{ route('admin.enfermeiro.update', $enfermeiro->idEnfermeiroPK) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Dados do Enfermeiro -->
      <label for="nomeEnfermeiro">Nome do Enfermeiro</label>
      <input type="text" name="nomeEnfermeiro" id="nomeEnfermeiro" value="{{ $enfermeiro->nomeEnfermeiro }}" required>

      <label for="corenEnfermeiro">COREN</label>
      <input type="text" name="corenEnfermeiro" id="corenEnfermeiro" value="{{ $enfermeiro->corenEnfermeiro }}" required>


      <label for="genero">Gênero</label>
      <select name="genero" id="genero" required>
        <option value="">Selecione</option>
        <option value="Masculino" {{ $enfermeiro->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
        <option value="Feminino" {{ $enfermeiro->genero == 'Feminino' ? 'selected' : '' }}>Feminino</option>
        <option value="Outro" {{ $enfermeiro->genero == 'Outro' ? 'selected' : '' }}>Outro</option>
      </select>

      <label for="emailEnfermeiro">Email do Enfermeiro</label>
      <input type="email" name="emailEnfermeiro" id="emailEnfermeiro" value="{{ $enfermeiro->emailEnfermeiro }}" required>

      <!-- Dados do Usuário vinculado -->
   <label for="nomeUsuario">Nome do Usuário</label>
      <input type="text" name="nomeUsuario" id="nomeUsuario" value="{{ $enfermeiro->usuario->nomeUsuario }}" required>

      <label for="emailUsuario">Email do Usuário</label>
      <input type="email" name="emailUsuario" id="emailUsuario" value="{{ $enfermeiro->usuario->emailUsuario }}" required>

      <button type="submit">Salvar Alterações</button>
    </form>
  </div>
</main>

@endsection
