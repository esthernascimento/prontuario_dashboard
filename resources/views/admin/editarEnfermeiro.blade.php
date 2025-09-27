@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/editarEnfermeiro.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-pencil-square icon"></i>
            <h1>Editar Enfermeiro(a)</h1>
        </div>

        <form action="{{ route('admin.enfermeiro.update', $enfermeiro->idEnfermeiroPK) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- TÍTULO: Dados do(a) Enfermeiro(a) --}}
            <div class="form-section-title">Dados do(a) Enfermeiro(a)</div>

            {{-- Linha 1: Nome do Enfermeiro --}}
            <div class="input-group">
                <label for="nomeEnfermeiro">Nome do Enfermeiro</label>
                <input type="text" name="nomeEnfermeiro" id="nomeEnfermeiro" value="{{ $enfermeiro->nomeEnfermeiro }}" required>
            </div>

            {{-- Linha 2: COREN --}}
            <div class="input-group">
                <label for="corenEnfermeiro">COREN</label>
                <input type="text" name="corenEnfermeiro" id="corenEnfermeiro" value="{{ $enfermeiro->corenEnfermeiro }}" required>
            </div>

            {{-- Linha 3: Gênero e Email (Lado a Lado - **SPLIT GROUP**) --}}
            <div class="split-group">
                {{-- Campo Gênero --}}
                <div class="input-group">
                    <label for="genero">Gênero</label>
                    <select name="genero" id="genero" class="custom-select" required>
                        <option value="Feminino" {{ $enfermeiro->genero == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                        <option value="Masculino" {{ $enfermeiro->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Outro" {{ $enfermeiro->genero == 'Outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                {{-- Campo Email do Enfermeiro --}}
                <div class="input-group">
                    <label for="emailEnfermeiro">Email do Enfermeiro</label>
                    <input type="email" name="emailEnfermeiro" id="emailEnfermeiro" value="{{ $enfermeiro->emailEnfermeiro }}" required>
                </div>
            </div>

            <hr class="section-separator">

            {{-- TÍTULO: Dados de Acesso (Login) --}}
            <div class="form-section-title">Dados de Acesso (Login)</div>

            {{-- Linha 4: Nome do Usuário --}}
            <div class="input-group">
                <label for="nomeUsuario">Nome do Usuário</label>
                <input type="text" name="nomeUsuario" id="nomeUsuario" value="{{ $enfermeiro->usuario->nomeUsuario }}" required>
            </div>

            {{-- Linha 5: Email do Usuário --}}
            <div class="input-group">
                <label for="emailUsuario">Email do Usuário</label>
                <input type="email" name="emailUsuario" id="emailUsuario" value="{{ $enfermeiro->usuario->emailUsuario }}" required>
            </div>

            <button type="submit" class="save-button">Salvar Alterações</button>
        </form>
    </div>
</main>

@endsection