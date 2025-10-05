<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+ | Pré-Cadastro Paciente</title>
  <link rel="stylesheet" href="{{ url('/css/admin/loginAdm.css') }}"> <!-- reutilizando o mesmo CSS -->
  <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
  <main class="main-container">

    <!-- Lado Esquerdo -->
    <div class="left-side">
      <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo ilustrativa">
    </div>

    <div class="right-side">
      <div class="login-content fade-in">
    
        <h2>Pré-Cadastro de Paciente</h2>

        <form class="cads-card" action="/api/pacientes" method="POST">
          @csrf

          @if($errors->any())
            <div class="alert-error">
              {{ $errors->first() }}
            </div>
          @endif

          <div class="input-group">
            <label for="nomePaciente">Nome</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user icon-left"></i>
              <input type="text" id="nomePaciente" name="nomePaciente" value="{{ old('nomePaciente') }}" required />
            </div>
          </div>

          <div class="input-group">
            <label for="cpfPaciente">CPF</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-card icon-left"></i>
              <input type="text" id="cpfPaciente" name="cpfPaciente" value="{{ old('cpfPaciente') }}" required />
            </div>
          </div>

          <div class="input-group">
            <label for="dataNascPaciente">Data de Nascimento</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-calendar-days icon-left"></i>
              <input type="date" id="dataNascPaciente" name="dataNascPaciente" value="{{ old('dataNascPaciente') }}" required />
            </div>
          </div>

          <div class="input-group">
            <label for="cartaoSusPaciente">Cartão SUS</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-badge icon-left"></i>
              <input type="text" id="cartaoSusPaciente" name="cartaoSusPaciente" value="{{ old('cartaoSusPaciente') }}" required />
            </div>
          </div>

          <div class="input-group" style="margin-top: 15px;">
            <label for="statusPaciente" style="display: flex; align-items: center; gap: 10px;">
              <input type="checkbox" id="statusPaciente" name="statusPaciente" value="1" checked />
              <span>Paciente Ativo</span>
            </label>
          </div>

          <button type="submit" class="btn-login">CADASTRAR</button>
        </form>
      </div>
    </div>

  </main>

  <script>

    const inputs = document.querySelectorAll(".input-wrapper input");

    inputs.forEach(input => {
      input.addEventListener("focus", () => {
        input.parentElement.classList.add("focused");
      });
      input.addEventListener("blur", () => {
        if (input.value === "") {
          input.parentElement.classList.remove("focused");
        }
      });
    });
  </script>
</body>
</html>
