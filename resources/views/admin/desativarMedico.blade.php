<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desativar Médico - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/admin/desativarMedico.css') }}">
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

  <!-- Conteúdo principal -->
  <main class="main-dashboard">
    <div class="cadastrar-container" style="text-align:center;">
      <div class="cadastrar-header">
        
        <i class="bi bi-trash-fill"></i>
        <h1>Desativar Médico</h1>
      </div>

      <p>Tem certeza que deseja desativar o médico<b></b>?</p>

      <form action="#" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn-desativar">
        Sim, desativar
        </button>
      </form>

      <a href="{{ route('admin.manutencaoMedicos') }}" class="btn-cancelar">
        Cancelar
      </a>
    </div>
  </main>

</body>
</html>
