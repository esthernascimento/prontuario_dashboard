<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Segurança - Prontuário+</title>


  <link rel="stylesheet" href="{{url('/css/admin/dashboardAdm.css')}}">
  <link rel="stylesheet" href="{{url('/css/admin/seguranca.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

  <div class="sidebar">
    <img src="{{asset('img/adm-logo2.png')}}" class="logo">
    <nav>
      <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
      <a href="{{ route('admin.pacientes') }}"><i class="bi bi-people-fill"></i></a>
      <a href="{{ route('admin.manutencaoMedicos') }}"><i class="bi bi-plus-circle-fill"></i></a>
      <a href="{{ route('admin.ajuda') }}"><i class="bi bi-question-circle-fill"></i></a>
      <a href="{{ route('admin.seguranca') }}"><i class="bi bi-shield-lock-fill"></i></a>
      <a href="{{ route('admin.logout') }}"><i class="bi bi-power"></i></a>
    </nav>
  </div>

  <div class="main-dashboard-wrapper">
    <header class="header">
      <div class="user-info">
        <img src="{{ asset('img/julia.png')}}" alt="Dra. Júlia">
        <span>Dra. Júlia Marcelli</span>
      </div>
    </header>

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
  </div>