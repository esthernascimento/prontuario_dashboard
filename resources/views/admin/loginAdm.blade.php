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

    <div class="logo-area">
      <img src="{{asset('img/adm-logo2.png')}}" class="logo">
    </div>

    <div class="login-area">
      <form class="login-card" action="{{ route('admin.login') }}" method="POST">

        @csrf

        <h2>Administrador Login</h2>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <label for="emailAdmin">E-mail</label>
        <input type="email" id="emailAdmin" name="emailAdmin" value="{{ old('emailAdmin') }}" required />
      
        <label for="senhaAdmin">Senha</label>
        <input type="password" id="senhaAdmin" name="senhaAdmin" required />
      
        <button class="button" type="submit">ENTRAR</button>
      
      </form>
    </div>
  </main>
</body>
</html>
