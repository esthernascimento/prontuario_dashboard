<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciamento de Médicos - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/admin/manutencaoMedicos.css') }}">
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
      <a href="{{ route('admin.logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-power"></i>
      </a>
      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-power"></i>
      </a>
    </nav>
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
    <div class="medico-container">
      <div class="medico-header">
        <h1><i class="bi bi-person-vcard-fill"></i> Gerenciamento de Médicos</h1>

        <a href="{{ route('admin.medicos.create') }}" class="btn-add-medico">
          <i class="bi bi-plus-circle"></i> Cadastrar Médico
        </a>
      </div>

      <div class="box-table">
        <table>
          <thead>
            <tr>
              <th>Nome Médico</th>
              <th>CRM</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($medicos as $medico)
            <tr>
              <td>{{ $medico->nomeMedico }}</td>
              <td>{{ $medico->crmMedico }}</td>
              <td class="actions">
                <a href="{{ route('admin.medicos.editar', $medico->idMedicoPK) }}">
                  <i class="bi bi-pencil" title="Editar"></i>
                </a>
                <a href="#"><i class="bi bi-slash-circle" title="Desativar"></i></a>
                <a href="{{ route('admin.medicos.confirmarExclusao', $medico->idMedicoPK) }}">
                  <i class="bi bi-trash" title="Excluir"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>

</html>