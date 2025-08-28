<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Prontuário+</title>

  <link rel="stylesheet" href="{{url('/css/loginMedico.css')}}">
  <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />

</head>
<body>
  <main class="main-container">

    <!-- Lado azul com a logo -->
    <div class="logo-area">
      <img src="{{asset('img/medico-logo2.png')}}" class="logo">
    </div>

    <!-- Card de login -->
    <div class="login-area">
      <form class="login-card" action="login.php" method="POST">
        <h2>Médico(a) Login</h2>
        <label for="crm">CRM</label>
        <input type="text" id="crm" name="crm" required />
      
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required />
      
        <button class="button" type="submit">ENTRAR</button>
      
        <a href="{{url('/cadastroMedico')}}">Não tem cadastro? <strong>Clique aqui</strong></a>
      </form>
    </div>      
