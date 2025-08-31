<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Médico - Prontuário+</title>

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

  <main class="main-dashboard">
    <div class="cadastrar-container">
      <div class="cadastrar-header">
        <i class="bi bi-pencil-square"></i>
        <h1>Editar Médico</h1>
      </div>

      <form action="#" method="POST" enctype="multipart/form-data" class="form-cadastrar">
        @csrf
        @method('PUT')

        <!-- Upload de foto -->
        <div class="foto-upload">
  
          <label for="foto">
            <i class="bi bi-camera"></i>
            <span>Alterar Foto</span>
          </label>
          <input type="file" id="foto" name="foto" accept="image/*" hidden>
        </div>

        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" value="#" required>
        </div>

        <div class="form-group">
          <label for="telefone">Telefone</label>
          <input type="text" id="telefone" name="telefone" value="#" required>
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" value="#" required>
        </div>

        <div class="form-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Nova senha">
        </div>

        <button type="submit" class="btn-salvar">Salvar Alterações</button>
      </form>
    </div>
  </main>

</body>

</html>
