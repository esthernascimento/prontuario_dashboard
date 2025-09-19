@extends('admin.templates.admTemplate')

    @php $admin = auth()->guard('admin')->user(); @endphp

  
  </div>

  <main class="main-dashboard">
    <div class="cadastrar-container">
      <div class="cadastrar-header">
        <i class="bi bi-pencil-square"></i>
        <h1>Editar Médico</h1>
      </div>

      <form action="{{ route('admin.medicos.update', $medico->idMedicoPK) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Dados do Médico -->
        <input type="text" name="nomeMedico" value="{{ $medico->nomeMedico }}" required>

        <!-- Dados do Usuário -->
        <input type="text" name="nomeUsuario" value="{{ $medico->usuario->nomeUsuario }}" required>
        <input type="email" name="emailUsuario" value="{{ $medico->usuario->emailUsuario }}" required>

        <button type="submit">Salvar Alterações</button>
      </form>

    </div>
  </main>

  @endsection
