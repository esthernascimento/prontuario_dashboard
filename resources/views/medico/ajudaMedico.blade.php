@extends('medico.templates.medicoTemplate')

@section('title', 'Central de Ajuda')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/MedicoAjuda.css') }}">

<main class="main-dashboardAjuda">
  <div class="help-container">
    <h1><i class="bi bi-person-heart"></i> Central de Ajuda</h1>

    <h2><i class="bi bi-question-circle-fill"></i> Perguntas Frequentes (FAQ)</h2>

    <details>
      <summary>Como eu visualizo um prontuário?</summary>
      <p>Para visualizar um prontuário, vá para a seção "Prontuário" e clique no ícone de visualização ao lado do paciente.</p>
    </details>

    <details>
      <summary>O que significa o card "Pacientes no sistema"?</summary>
      <p>O card mostra a quantidade total de pacientes cadastrados no sistema Prontuário+.</p>
    </details>

    <details>
      <summary>Como eu altero minha senha?</summary>
      <p>No menu lateral, clique no ícone de cadeado (<i class="bi bi-shield-lock-fill"></i>). Na página de segurança, você poderá definir uma nova senha.</p>
    </details>

    <h2><i class="bi bi-headset"></i> Não encontrou o que procurava?</h2>
    <p>Envie sua dúvida diretamente para nossa equipe de suporte através do formulário abaixo.</p>

    <form action="#" method="POST" class="contact-form">
      <input type="text" name="assunto" placeholder="Assunto" required>
      <textarea name="mensagem" rows="6" placeholder="Digite sua mensagem aqui..." required></textarea>
      <button type="submit">Enviar Mensagem</button>
    </form>
  </div>
</main>
@endsection
