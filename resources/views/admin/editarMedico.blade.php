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
    @php $admin = auth()->guard('admin')->user(); @endphp

  <div class="sidebar">
    <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo Prontuário+" class="logo">
    <nav>
      <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
      <a href="{{ route('admin.pacientes') }}"><i class="bi bi-people-fill"></i></a>
      <a href="{{ route('admin.manutencaoMedicos') }}"><i class="bi bi-plus-circle-fill"></i></a>
      <a href="{{ route('admin.ajuda') }}"><i class="bi bi-question-circle-fill"></i></a>
      <a href="{{ route('admin.seguranca') }}"><i class="bi bi-shield-lock-fill"></i></a>
      <a href="{{ route('admin.logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-power"></i>
      </a>
      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>    </nav>
  </div>

  <div class="main-dashboard-wrapper">
    <header class="header">
      <a href="{{ route('admin.perfil') }}" class="user-info" style="text-decoration: none; color: inherit;">
        @if($admin && $admin->foto)
          <img src="{{ asset('storage/fotos/' . $admin->foto) }}" alt="Foto do Admin">
        @else
          <img src="{{ asset('img/teste.png') }}" alt="Foto padrão">
        @endif
        <span>{{ $admin->nomeAdmin ?? 'Administrador' }}</span>
      </a>
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

        <!-- Dados do Médico -->
        <input type="text" name="nomeMedico" value="{{ $medico->nomeMedico }}" required>

        <!-- Dados do Usuário -->
        <input type="text" name="nomeUsuario" value="{{ $medico->usuario->nomeUsuario }}" required>
        <input type="email" name="emailUsuario" value="{{ $medico->usuario->emailUsuario }}" required>

        <button type="submit">Salvar Alterações</button>
      </form>

    </div>
  </main>

</body>

</html>