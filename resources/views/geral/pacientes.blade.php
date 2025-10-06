@extends('admin.templates.admTemplate')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/pacientes.css') }}">

<main class="main-dashboard">
    <div class="patients-container">
        <div class="patients-header">
            <h1><i class="bi bi-people-fill"></i> Gerenciamento de Pacientes</h1>
            {{-- A rota create agora funciona corretamente com o novo controller --}}
            <a href="{{ route('admin.pacientes.create') }}" class="btn-add-paciente">
                <i class="bi bi-plus-circle"></i> Cadastrar Paciente
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin: 15px; padding: 15px; background-color: #d4edda; border-color: #c3e6cb; color: #155724; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulário de Filtros que envia os dados para o AdminPacienteController -->
        <form action="{{ route('admin.pacientes.index') }}" method="GET" class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Pesquisar por nome, CPF ou SUS..." value="{{ request('search') }}">
            </div>
            
            {{-- Usar SELECT nativo é mais simples e acessível --}}
            <select name="age" class="custom-select-native" onchange="this.form.submit()">
                <option value="">Todas as idades</option>
                <option value="crianca" @selected(request('age') == 'crianca')>Crianças (0-12)</option>
                <option value="adolescente" @selected(request('age') == 'adolescente')>Adolescentes (13-17)</option>
                <option value="adulto" @selected(request('age') == 'adulto')>Adultos (18-59)</option>
                <option value="idoso" @selected(request('age') == 'idoso')>Idosos (60+)</option>
            </select>

            <select name="gender" class="custom-select-native" onchange="this.form.submit()">
                <option value="">Todos os gêneros</option>
                <option value="M" @selected(request('gender') == 'M')>Masculino</option>
                <option value="F" @selected(request('gender') == 'F')>Feminino</option>
            </select>
            
            <button type="submit" class="btn-filter">Filtrar</button>
        </form>

        <div class="table-wrapper">
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>NOME</th>
                        <th>CPF</th>
                        <th>IDADE</th>
                        <th>STATUS</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                        <tr>
                            <td>{{ $paciente->nomePaciente }}</td>
                            <td>{{ $paciente->cpfPaciente }}</td>
                            <td>{{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->age }} anos</td>
                            <td>{{ ucfirst($paciente->statusPaciente) }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.pacientes.edit', $paciente) }}" class="btn-edit" title="Editar"><i class="bi bi-pencil"></i></a>
                                    
                                    <form action="{{ route('admin.pacientes.destroy', $paciente) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir o paciente {{ $paciente->nomePaciente }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Excluir"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-patients">Nenhum paciente encontrado com os filtros aplicados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{-- A paginação agora funciona perfeitamente com os filtros --}}
            {{ $pacientes->links() }}
        </div>
    </div>
</main>

{{-- O SCRIPT DE FILTRO EM JAVASCRIPT FOI REMOVIDO --}}
{{-- Ele não é mais necessário --}}

@endsection
