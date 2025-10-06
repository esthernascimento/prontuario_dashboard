<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+ | Login ADM</title>
  <link rel="stylesheet" href="{{url('/css/admin/loginAdm.css')}}">
  <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
  <main class="main-container">
    
    <!-- Lado Esquerdo -->
    <div class="left-side">
      <img src="{{ asset('img/adm-logo2.png') }}" alt="Ilustração administrativa">
    </div>

    <!-- Lado Direito -->
    <div class="right-side">
      <div class="login-content fade-in">
        <div class="logo">
          <img src="{{ asset('img/icon-loginAdm.png') }}" alt="Logo Prontuário+">
        </div>

        <h2>Login ADM</h2>

        <form action="{{ route('admin.login') }}" method="POST">
          @csrf

          @if($errors->any())
            <div class="alert-error">
              {{ $errors->first() }}
            </div>
          @endif

          <div class="input-group">
            <label for="emailAdmin">E-mail</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user icon-left"></i>
              <input type="email" id="emailAdmin" name="emailAdmin" value="{{ old('emailAdmin') }}" required />
            </div>
          </div>

          <div class="input-group">
            <label for="senhaAdmin">Senha</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-lock icon-left"></i>
              <input type="password" id="senhaAdmin" name="senhaAdmin" required />
              <i id="togglePassword" class="fa-solid fa-eye-slash icon-right"></i>
            </div>
          </div>

          <button type="submit" class="btn-login">ENTRAR</button>
        </form>
      </div>
    </div>
  </main>

  <script>
    // Função de exibir/ocultar senha
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("senhaAdmin");

    togglePassword.addEventListener("click", () => {
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";
      togglePassword.classList.toggle("fa-eye");
      togglePassword.classList.toggle("fa-eye-slash");
    });

    // Efeito de foco no input
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
