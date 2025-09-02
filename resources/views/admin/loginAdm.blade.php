<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Prontuário+ | Admin Login</title>

  <link rel="stylesheet" href="{{url('/css/admin/loginAdm.css')}}">
  <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />

</head>
<body>
  <main class="main-container">

    <!-- Lado azul com a logo -->
    <div class="logo-area">
      <img src="{{asset('img/adm-logo2.png')}}" class="logo">
    </div>

    <!-- Card de login -->
    <div class="login-area">
      <!-- O action agora aponta para a rota que criamos -->
      <form class="login-card" action="{{ route('admin.login') }}" method="POST">
        <!-- @csrf é ESSENCIAL para segurança em formulários Laravel -->
        @csrf

        <h2>Administrador Login</h2>

        <!-- Exibe erros de validação, se houver -->
        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <label for="emailAdmin">E-mail</label>
        <!-- O 'name' deve corresponder ao que o controller espera -->
        <input type="email" id="emailAdmin" name="emailAdmin" value="{{ old('emailAdmin') }}" required />
      
        <label for="senhaAdmin">Senha</label>
        <input type="password" id="senhaAdmin" name="senhaAdmin" required />
      
        <button class="button" type="submit">ENTRAR</button>
      
        <!-- Removi o link de cadastro, já que será feito via tinker -->
      </form>
    </div>
  </main>
</body>
</html>
