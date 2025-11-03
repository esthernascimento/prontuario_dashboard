@extends('unidade.templates.unidadeTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/unidade/desativarRecepcionista.css') }}">

<main class="main-dashboard">
  <div class="cadastrar-container" style="text-align:center;">
    <div class="cadastrar-header">
      
      <i class="bi bi-trash-fill"></i>
      <h1>Excluir Recepcionista</h1>
    </div>

    <p>Tem certeza que deseja excluir o(a) recepcionista {{ $recepcionista->nomeRecepcionista }}?</p>

    <form action="{{ route('unidade.recepcionista.excluir', $recepcionista->idRecepcionistaPK) }}" method="POST">
      @csrf
      @method('DELETE')
      
      <button type="submit" class="btn-desativar">
        Sim, excluir
      </button>
    </form>

    <a href="{{ route('unidade.manutencaoRecepcionista') }}" class="btn-cancelar">
      Cancelar
    </a>
  </div>
</main>

@endsection