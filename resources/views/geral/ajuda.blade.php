@extends('geral.templates.geralTemplate')

@section('content') 
  @php $admin = auth()->guard('admin')->user(); @endphp

  @section('title', 'Dashboard - Painel Administrativo')

    <link rel="stylesheet" href="{{ asset('css/geral/ajuda.css') }}">

    <main class="main-dashboardAjuda">
      <div class="help-container">
        <h1><i class="bi bi-person-heart"></i>Central de Ajuda</h1>
        <p>Olá, Dra. Júlia! Como podemos ajudar hoje?</p>

        <h2><i class="bi bi-question-circle-fill"></i> Perguntas Frequentes (FAQ)</h2>

        <details>
          <summary>Como eu cadastro um novo paciente?</summary>
          <p>Para cadastrar um novo paciente, vá para a seção "Pacientes" no menu lateral, clique no botão "+ Novo
            Paciente", preencha as informações e clique em "Salvar".</p>
        </details>

        <details>
          <summary>O que significa o card "Exames Pendentes"?</summary>
          <p>O card "Exames Pendentes" mostra a quantidade de resultados de exames que foram solicitados mas ainda não
            foram anexados ao prontuário do paciente no sistema.</p>
        </details>

        <details>
          <summary>Como eu altero minha senha?</summary>
          <p>No menu lateral, clique no ícone de cadeado (<i class="bi bi-shield-lock-fill"></i>). Na página de
            segurança, você encontrará a opção para definir uma nova senha.</p>
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
    
  
    @endsection