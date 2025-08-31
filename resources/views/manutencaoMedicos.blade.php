<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>manutencaoMedicos - Prontuário+</title>

  <link rel="stylesheet" href="{{ asset('css/manutencaoMedicos.css') }}">
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
    <div class="medico-container">
      <div class="medico-header">
        <h1><i class="bi bi-person-vcard-fill"></i> Gerenciamento de Médicos</h1>

        <button class="btn-add-medico" onclick='adicionar-medico.php'>
       
          <i class=" bi bi-plus-circle"></i> Cadastrar Médico
        </button>
      </div>

      <div class="box-table">
        <table>
          <thead>
            <tr>
              <th>Nome Médico</th>
              <th>E-mail Médico</th>
              <th>Senha</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Dra. Maria Souza</td>
              <td>maria.souza@clinica.com</td>
              <td>123</td>
              <td class="actions">
                <i class="bi bi-pencil"></i>
                <i class="bi bi-slash-circle"></i>
                <i class="bi bi-trash"></i>
              </td>
            </tr>
            <tr>
              <td>Dra. Maria Souza</td>
              <td>maria.souza@clinica.comm</td>
              <td>123</td>
              <td class="actions">
                <i class="bi bi-pencil"></i>
                <i class="bi bi-slash-circle"></i>
                <i class="bi bi-trash"></i>
              </td>
            </tr>
            <tr>
              <td>Dra. Maria Souza</td>
              <td>maria.souza@clinica.com</td>
              <td>123</td>
              <td class="actions">
                <i class="bi bi-pencil"></i>
                <i class="bi bi-slash-circle"></i>
                <i class="bi bi-trash"></i>
              </td>
            </tr>
            <tr>
              <td>Dra. Maria Souza</td>
              <td>maria.souza@clinica.com</td>
              <td>123</td>
              <td class="actions">
                <i class="bi bi-pencil"></i>
                <i class="bi bi-slash-circle"></i>
                <i class="bi bi-trash"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  </main>

</body>

</html>