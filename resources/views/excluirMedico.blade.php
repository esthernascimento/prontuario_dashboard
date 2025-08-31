<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Excluir Médico - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/cadastrarMedico.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

  <div class="sidebar">
    <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo Prontuário+" class="logo">
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
        <img src="{{ asset('img/julia.png') }}" alt="Foto da Dra. Júlia">
        <span>Dra. Júlia Marcelli</span>
      </div>
    </header>
  </div>

  <!-- Conteúdo principal -->
  <main class="main-dashboard">
    <div class="cadastrar-container" style="text-align:center;">
      <div class="cadastrar-header">
        
        <i class="bi bi-trash-fill"></i>
        <h1>Excluir Médico</h1>
      </div>

      <p>Tem certeza que deseja excluir o médico <b></b>?</p>

      <form action="#" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn-excluir">
          Sim, excluir
        </button>
      </form>

      <a href="{{ route('admin.manutencaoMedicos') }}" class="btn-excluir">
        Cancelar
      </a>
    </div>
  </main>

</body>
</html>
