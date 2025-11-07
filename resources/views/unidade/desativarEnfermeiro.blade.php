@extends('unidade.templates.unidadeTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/unidade/desativarMedico.css') }}">

<main class="main-dashboard">
  <div class="cadastrar-container" style="text-align:center;">
    <div class="cadastrar-header">
      
      <i class="bi bi-trash-fill"></i>
      <h1>Excluir Enfermeiro(a)</h1>
    </div>

    <p>Tem certeza que deseja excluir o(a) enfermeiro(a) {{ $enfermeiro->nomeEnfermeiro }}?</p>

    <form action="{{ route('unidade.enfermeiro.excluir', $enfermeiro->idEnfermeiroPK) }}" method="POST">
      @csrf
      @method('DELETE')
      
      <button type="submit" class="btn-desativar">
        Sim, excluir
      </button>
    </form>

    <a href="{{ route('unidade.manutencaoEnfermeiro') }}" class="btn-cancelar">
      Cancelar
    </a>
  </div>
</main>

@endsection