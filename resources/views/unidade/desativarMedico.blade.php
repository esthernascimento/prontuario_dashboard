@extends('unidade.templates.unidadeTemplate')

@section('content')

  <link rel="stylesheet" href="{{ asset('css/unidade/desativarMedico.css') }}">

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

      <a href="{{ route('unidade.manutencaoMedicos') }}" class="btn-cancelar">
        Cancelar
      </a>
    </div>
  </main>

@endsection