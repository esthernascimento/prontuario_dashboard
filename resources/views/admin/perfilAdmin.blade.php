<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil do Administrador - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/admin/perfilAdmin.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <script>
    window.onload = function() {
      @if(session('success'))
      setTimeout(function() {
        window.location.href = "{{ route('admin.dashboard') }}";
      }, 3000);
      @endif

      @if(session('error'))
      setTimeout(function() {
        window.location.href = "{{ route('admin.perfil') }}";
      }, 3000);
      @endif
    };
  </script>
</head>

<body>
  @php $admin = auth()->guard('admin')->user(); @endphp

  <!-- Sidebar -->
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
      </form>
    </nav>
  </div>

  <!-- Header -->
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

  <!-- Conteúdo Principal -->
  <main class="main-dashboard">
    <div class="cadastrar-container">
      <div class="cadastrar-header">
        <i class="bi bi-person-circle"></i>
        <h1>Perfil do Administrador</h1>
      </div>

      <form action="{{ route('admin.perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Foto -->
        <div class="foto-upload">
          <label for="foto">
            <i class="bi bi-camera"></i>
            <span>Alterar Foto</span>
          </label>
          <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
          <img id="preview-img" src="{{ $admin->foto ? asset('storage/fotos/' . $admin->foto) : asset('img/teste.png') }}" alt="Foto atual" style="width: 100px; border-radius: 10px; margin-top: 10px;">
        </div>

        <!-- Dados -->
        <input type="text" name="nomeAdmin" value="{{ $admin->nomeAdmin }}" required>
        <input type="email" name="emailAdmin" value="{{ $admin->emailAdmin }}" required>
        <input type="password" name="senhaAdmin" placeholder="Nova senha (opcional)">

        <button type="submit">Salvar Alterações</button>
      </form>
    </div>
  </main>

  <!-- Preview da Foto -->
  <script>
    function previewFoto(event) {
      const input = event.target;
      const preview = document.getElementById('preview-img');

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>

</html>