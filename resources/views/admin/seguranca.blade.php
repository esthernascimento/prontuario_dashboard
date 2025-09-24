@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/seguranca.css') }}">


  @php $admin = auth()->guard('admin')->user(); @endphp

    <main class="main-dashboard">
      <div class="security-container">
        <h1><i class="bi bi-shield-lock-fill"></i> Configurações de Segurança</h1>
        <p>Gerencie suas configurações de segurança e privacidade</p>

        <!-- Seção de Alteração de Senha -->
        <div class="security-section">
          <h2><i class="bi bi-key-fill"></i> Alterar Senha</h2>

          {{-- Mensagens de sucesso e erro --}}
          @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
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

          <form action="{{ route('admin.alterarSenha') }}" method="POST" class="security-form">
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


        <!-- Seção de Backup e Recuperação -->
        <div class="security-section">
          <h2><i class="bi bi-download"></i> Backup e Recuperação</h2>
          <div class="backup-section">
            <div class="backup-info">
              <p>Faça backup dos seus dados importantes regularmente</p>
              <p><strong>Último backup:</strong> 10/07/2025 às 03:00</p>
            </div>
            <div class="backup-actions">
              <button class="btn-secondary">Fazer Backup Agora</button>
              <button class="btn-outline">Restaurar Backup</button>
            </div>
          </div>
        </div>

      </div>
    </main>

    @endsection