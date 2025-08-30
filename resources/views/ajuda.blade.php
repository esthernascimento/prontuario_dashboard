<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Ajuda - Prontuário+</title>
  <link rel="stylesheet" href="{{url('/css/dashboardAdm.css')}}">
  <link rel="stylesheet" href="{{url('/css/ajuda.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
</head>
<body>
    <div class="sidebar">
    <img src="{{asset('img/adm-logo2.png')}}" class="logo">
    <nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
    <a href="{{ route('admin.pacientes') }}"><i class="bi bi-people-fill"></i></a>
    <a href="{{ route('admin.ajuda') }}"><i class="bi bi-question-circle-fill"></i></a>
    <a href="{{ route('admin.seguranca') }}"><i class="bi bi-shield-lock-fill"></i></a>
    <a href="#"><i class="bi bi-plus-circle-fill"></i></a>
    <a href="{{ route('admin.logout') }}"><i class="bi bi-power"></i></a>
</nav>
    </div>

    <div class="main-dashboard-wrapper">
    <header class="header">
        <div class="user-info">
        <img src="{{ asset('img/julia.png')}}" alt="Dra. Júlia">
        <span>Dra. Júlia Marcelli</span>
        </div>
    </header>


      <main class="main-dashboard">
        <div class="help-container">
          <h1>Central de Ajuda</h1>
          <p>Olá, Dra. Júlia! Como podemos ajudar hoje?</p>

          <h2><i class="bi bi-question-circle-fill"></i> Perguntas Frequentes (FAQ)</h2>
          
          <details>
            <summary>Como eu cadastro um novo paciente?</summary>
            <p>Para cadastrar um novo paciente, vá para a seção "Pacientes" no menu lateral, clique no botão "+ Novo Paciente", preencha as informações e clique em "Salvar".</p>
          </details>

          <details>
            <summary>O que significa o card "Exames Pendentes"?</summary>
            <p>O card "Exames Pendentes" mostra a quantidade de resultados de exames que foram solicitados mas ainda não foram anexados ao prontuário do paciente no sistema.</p>
          </details>
          
          <details>
            <summary>Como eu altero minha senha?</summary>
            <p>No menu lateral, clique no ícone de cadeado (<i class="bi bi-shield-lock-fill"></i>). Na página de segurança, você encontrará a opção para definir uma nova senha.</p>
          </details>

          <h2><i class="bi bi-headset"></i> Não encontrou o que procurava?</h2>
          <p>Envie sua dúvida diretamente para nossa equipe de suporte através do formulário abaixo.</p>
          
          <form action="enviar_ajuda.php" method="POST" class="contact-form">
            <input type="text" name="assunto" placeholder="Assunto" required>
            <textarea name="mensagem" rows="6" placeholder="Digite sua mensagem aqui..." required></textarea>
            <button type="submit">Enviar Mensagem</button>
          </form>

        </div>
      </main>
    </div>
</body>
</html>