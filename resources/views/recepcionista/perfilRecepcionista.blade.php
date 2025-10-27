@extends('layouts.recepcionista')

@section('content')
<div class="container">
    <h2>Meu Perfil</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('recepcionista.atualizarPerfil') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nomeRecepcionista">Nome:</label>
            <input type="text" name="nomeRecepcionista" value="{{ old('nomeRecepcionista', $recepcionista->nomeRecepcionista) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="emailRecepcionista">E-mail:</label>
            <input type="email" name="emailRecepcionista" value="{{ old('emailRecepcionista', $recepcionista->emailRecepcionista) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="senhaRecepcionista">Nova Senha:</label>
            <input type="password" name="senhaRecepcionista" class="form-control">
        </div>

        <div class="form-group">
            <label for="senhaRecepcionista_confirmation">Confirmar Senha:</label>
            <input type="password" name="senhaRecepcionista_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
    </form>
</div>
@endsection
