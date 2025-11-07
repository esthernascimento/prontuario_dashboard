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

    <div class="left-side">
      <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo Enfermeiro(a)">
    </div>

    <div class="right-side">
      <div class="login-content">
        <div class="logo">
          <img src="{{ asset('img/icon-unidade.png') }}" alt="Logo Prontuário+">
        </div>

        <h2>Login Unidade</h2>

        @if ($errors->any())
          <div class="notification error">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('unidade.login.submit') }}" method="POST">
          @csrf

          <div class="input-group">
            <label for="cnpjUnidade">CNPJ</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user icon-left"></i>
              <input type="text" id="cnpjUnidade" name="cnpjUnidade" value="{{ old('cnpjUnidade') }}" required />
            </div>
          </div>

          <div class="input-group">
            <label for="senhaUnidade">Senha</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-lock icon-left"></i>
              <input type="password" id="senhaUnidade" name="senhaUnidade" required />
              <i id="togglePassword" class="fa-solid fa-eye-slash icon-right"></i>
            </div>
          </div>

          <button class="btn-login" type="submit">ENTRAR</button>
        </form>

      </div>
    </div>
  </main>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("senhaUnidade"); 

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            togglePassword.classList.toggle("fa-eye");
            togglePassword.classList.toggle("fa-eye-slash");
        });
    }


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
      if (input.value !== "") {
          input.parentElement.classList.add("focused");
      }
    });
  </script>
</body>

</html>