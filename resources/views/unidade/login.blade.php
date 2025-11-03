<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+ - Login Unidade</title>

  <!-- Ícones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ url('/css/unidade/loginUnidade.css') }}">
  <link rel="shortcut icon" href="{{ url('img/logo-azul.png') }}" type="image/x-icon" />
</head>

<body>
  <main class="main-container">

    <!-- Lado esquerdo -->
    <div class="left-side">
      <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo Enfermeiro(a)">
    </div>

    <!-- Lado direito -->
    <div class="right-side">
      <div class="login-content">
        <div class="logo">
          <img src="{{ asset('img/icon-loginEnfermeiro.png') }}" alt="Logo Prontuário+">
        </div>

        <h2>Login Unidade</h2>

        @if ($errors->any())
          <div class="notification error">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('enfermeiro.login.submit') }}" method="POST">
          @csrf

          <div class="input-group">
            <label for="CNPJ">CNPJ</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user icon-left"></i>
              <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" required />
            </div>
          </div>

          <div class="input-group">
            <label for="senha">Senha</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-lock icon-left"></i>
              <input type="password" id="senha" name="senha" required />
              <i id="togglePassword" class="fa-solid fa-eye-slash icon-right"></i>
            </div>
          </div>

          <button class="btn-login" type="submit">ENTRAR</button>
        </form>
      </div>
    </div>
  </main>

  <script>
    // Alternar visibilidade da senha
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("senha");

    togglePassword.addEventListener("click", () => {
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";
      togglePassword.classList.toggle("fa-eye");
      togglePassword.classList.toggle("fa-eye-slash");
    });

    // Estilizar foco nos inputs
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
