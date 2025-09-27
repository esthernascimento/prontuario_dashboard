@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/seguranca.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
    <div class="security-container">
        <h1 class="main-title"><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
        <p class="main-subtitle">Gerencie suas configurações de segurança e privacidade</p>

        {{-- Mensagens de sucesso e erro GLOBAIS (acima da seção) --}}
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="security-section">
            <h2><i class="bi bi-key-fill"></i> Alterar Senha</h2>

            <form action="{{ route('admin.alterarSenha') }}" method="POST" class="security-form">
                @csrf
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                    {{-- Exibir erro específico para senha_atual --}}
                    @error('senha_atual')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                    <small class="form-hint">Mínimo de 8 caracteres, incluindo números e letras</small>
                    {{-- Exibir erro específico para nova_senha --}}
                    @error('nova_senha')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nova_senha_confirmation">Confirmar Nova Senha:</label>
                    <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" required>
                </div>
                <button type="submit" class="btn-primary">Alterar Senha</button>
            </form>
        </div>

    </div>
</main>

@endsection