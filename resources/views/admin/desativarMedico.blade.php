@extends('admin.templates.admTemplate')

@section('content')

  <link rel="stylesheet" href="{{ asset('css/admin/desativarMedico.css') }}">

  <!-- Conteúdo principal -->
  <main class="main-dashboard">
    <div class="cadastrar-container" style="text-align:center;">
      <div class="cadastrar-header">
        
        <i class="bi bi-trash-fill"></i>
        <h1>Desativar Médico</h1>
      </div>

      <p>Tem certeza que deseja desativar o médico<b></b>?</p>

      <form action="#" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn-desativar">
        Sim, desativar
        </button>
      </form>

      <a href="{{ route('admin.manutencaoMedicos') }}" class="btn-cancelar">
        Cancelar
      </a>
    </div>
  </main>

@endsection