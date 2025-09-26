@extends('admin.templates.admTemplate')

@section('title', 'Manutenção de Enfermeiros')

@section('content')
<div class="container">
    <h1>Manutenção de Enfermeiros</h1>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.enfermeiro.create') }}" class="btn btn-primary mb-3">Cadastrar Novo Enfermeiro</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>COREN</th>
                <th>Especialidade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enfermeiro as $enfermeiro)
                <tr>
                    <td>{{ $enfermeiro->nomeEnfermeiro }}</td>
                    <td>{{ $enfermeiro->emailEnfermeiro }}</td>
                    <td>{{ $enfermeiro->corenEnfermeiro }}</td>
                    <td>{{ $enfermeiro->especialidadeEnfermeiro ?? '-' }}</td>
                    <td>
                        @if($enfermeiro->usuario)
                            {{ $enfermeiro->usuario->statusAtivoUsuario ? 'Ativo' : 'Inativo' }}
                        @else
                            Não vinculado
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.enfermeiro.editar', $enfermeiro->idEnfermeiroPK) }}" class="btn btn-sm btn-warning">Editar</a>
                        <a href="{{ route('admin.enfermeiro.confirmarExclusao', $enfermeiro->idEnfermeiroPK) }}" class="btn btn-sm btn-danger">Excluir</a>
                        <form action="{{ route('admin.enfermeiro.toggleStatus', $enfermeiro->idEnfermeiroPK) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">Alternar Status</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
