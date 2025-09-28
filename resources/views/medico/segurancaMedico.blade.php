@extends('medico.templates.medicoTemplate')

@section('title', 'Segurança')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/MedicoSeguranca.css') }}">

<main class="main-dashboard">
  <div class="security-container">
    <h1><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
    <p>Gerencie suas configurações de segurança e privacidade</p>

    <div class="security-section">
      <h2><i class="bi bi-key-fill"></i> Alterar Senha</h2>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $erro)
              <li>{{ $erro }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('medico.alterarSenha') }}" method="POST" class="security-form">
        @csrf
        <div class="form-group">
          <label for="senha_atual">Senha Atual:</label>
          <input type="password" id="senha_atual" name="senha_atual" required>
        </div>
        <div class="form-group">
          <label for="nova_senha">Nova Senha:</label>
          <input type="password" id="nova_senha" name="nova_senha" required>
          <small class="form-hint">Mínimo de 8 caracteres, incluindo números e letras</small>
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
