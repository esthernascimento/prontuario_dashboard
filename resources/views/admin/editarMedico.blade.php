@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/editarMedico.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
  <div class="cadastrar-container">
    <div class="cadastrar-header">
      <i class="bi bi-pencil-square icon"></i>
      <h1>Editar Médico</h1>
    </div>

    <form action="{{ route('admin.medicos.update', $medico->idMedicoPK) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-section-title">Dados do(a) Médico(a)</div>

      {{-- Campo Nome do Médico --}}
      <div class="input-group">
        <input type="text" name="nomeMedico" id="nomeMedico"
          value="{{ $medico->nomeMedico ?? '' }}"
          placeholder="Nome do Médico"
          required>
      </div>

      {{-- Linha: Gênero e CRM (Lado a Lado - SPLIT GROUP) --}}
      <div class="split-group">
        {{-- Campo Gênero (50% da largura) --}}
        <div class="input-group">
          <select name="genero" id="genero" class="custom-select" required>
            <option value="">Gênero</option>
            <option value="Masculino" {{ ($medico->genero ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
            <option value="Feminino" {{ ($medico->genero ?? '') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
            <option value="Outro" {{ ($medico->genero ?? '') == 'Outro' ? 'selected' : '' }}>Outro</option>
          </select>
        </div>

        {{-- Campo CRM (50% da largura) --}}
        <div class="input-group">
          <input type="text" name="crmMedico" id="crmMedico"
            value="{{ $medico->crmMedico ?? '' }}"
            placeholder="CRM"
            required>
        </div>
      </div>


      <hr class="section-separator">


      <div class="form-section-title">Dados de Acesso (Login)</div>

      {{-- Campo Nome do Usuário --}}
      <div class="input-group">
        <input type="text" name="nomeUsuario" id="nomeUsuario"
          value="{{ $medico->usuario->nomeUsuario ?? '' }}"
          placeholder="Nome de Usuário"
          required>
      </div>

      {{-- Campo Email do Usuário --}}
      <div class="input-group">
        <input type="email" name="emailUsuario" id="emailUsuario"
          value="{{ $medico->usuario->emailUsuario ?? '' }}"
          placeholder="Email de Acesso"
          required>
      </div>

      <button type="submit" class="save-button">Salvar Alterações</button>
    </form>
  </div>
</main>

@endsection