<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Prontuário+</title>

  <link rel="stylesheet" href="{{url('/css/login.css')}}">
  <link rel="shortcut icon" href="{{url('img/logo-azul')}}" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">


</head>
<body>
  <main class="main-container">

    <!-- Lado azul com a logo -->
    <div class="logo-area">
      <img src="{{asset('img/logo-azul')}}" class="logo">
    </div>

    <!-- Card de login -->
    <div class="login-area">
      <form class="login-card" action="login.php" method="POST">
        <h2>Login</h2>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required />
      
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required />
      
        <button class="button" type="submit">ENTRAR</button>
      
        <a href="{{url('/cadastro')}}">Não tem cadastro? <strong>Clique aqui</strong></a>
      </form>
    </div>      
