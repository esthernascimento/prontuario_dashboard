<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Médico - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/admin/editarMedico.css') }}">
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

    <form action="{{ route('admin.medicos.update', $medico->idMedicoPK) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <!-- Foto -->
  <div class="foto-upload">
    <label for="foto">
      <i class="bi bi-camera"></i>
      <span>Alterar Foto</span>
    </label>
    <input type="file" id="foto" name="foto" accept="image/*" hidden>
    @if ($medico->usuario->foto ?? false)
      <img src="{{ asset('storage/fotos/' . $medico->usuario->foto) }}" alt="Foto atual" style="width: 100px;">
    @endif
  </div>

  <!-- Dados do Médico -->
  <input type="text" name="nomeMedico" value="{{ $medico->nomeMedico }}" required>
  <input type="text" name="crmMedico" value="{{ $medico->crmMedico }}" required>
  <input type="text" name="especialidadeMedico" value="{{ $medico->especialidadeMedico }}">

  <!-- Dados do Usuário -->
  <input type="text" name="nomeUsuario" value="{{ $medico->usuario->nomeUsuario }}" required>
  <input type="email" name="emailUsuario" value="{{ $medico->usuario->emailUsuario }}" required>
  <input type="password" name="senhaUsuario" placeholder="Nova senha (opcional)">

  <button type="submit">Salvar Alterações</button>
</form>

    </div>
  </main>

</body>

</html>
