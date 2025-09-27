@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content') 
  @php $enfermeiro = auth()->guard('enfermeiro')->user(); @endphp

  @section('title', 'Dashboard - Painel Administrativo')

    <link rel="stylesheet" href="{{ asset('css/enfermeiro/ajuda.css') }}">

    <main class="main-dashboardAjuda">
      <div class="help-container">
        <h1><i class="bi bi-person-heart"></i>Central de Ajuda</h1>
        

        <h2><i class="bi bi-question-circle-fill"></i> Perguntas Frequentes (FAQ)</h2>

        <details>
          <summary>Como eu visualizo um prontuário?</summary>
          <p>Para visualizar um prontuário, vá para a seção "Prontuário" e no paciente vc clica em visualizar
            "Prontuário", preencha as informações e clique em "Salvar".</p>
        </details>

        <details>
          <summary>O que significa o card "Pacientes no sistema"?</summary>
          <p>O card "Pacientes no sistema" mostra a quantidade de resultados de pacientes que são cadastrados no Prontuário+</p>
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