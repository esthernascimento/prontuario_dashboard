@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content') 
  @php $enfermeiro = auth()->guard('enfermeiro')->user(); @endphp

  @section('title', 'Dashboard - Painel Administrativo')

    <link rel="stylesheet" href="{{ asset('css/enfermeiro/ajuda.css') }}">

    <main class="main-dashboardAjuda">
      <div class="help-container">
        
        <div class="title-bar">
        <h1><i class="bi bi-person-heart"></i>Central de Ajuda</h1>
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="faq-search" onkeyup="filterFAQ()" placeholder="Pesquisar nas Perguntas Frequentes...">
        </div>
        </div>
                <h2><i class="bi bi-question-circle-fill"></i> Perguntas Frequentes (FAQ)</h2>

        <p id="no-results-message" style="display: none; color: #a00; font-weight: bold; margin-top: 15px;">
            Nenhuma pergunta encontrada. Por favor, digite sua dúvida no formulário abaixo.
        </p>

        <div id="faq-list">
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
        </div>
                <h2><i class="bi bi-headset"></i> Não encontrou o que procurava?</h2>
        <p>Envie sua dúvida diretamente para nossa equipe de suporte através do formulário abaixo.</p>

        <form action="enviar_ajuda.php" method="POST" class="contact-form">
          <input type="text" name="assunto" placeholder="Assunto" required>
          <textarea name="mensagem" rows="6" placeholder="Digite sua mensagem aqui..." required></textarea>
          <button type="submit">Enviar Mensagem</button>
        </form>

      </div>
    </main>
    
    <script>
    // ... (Coloque a função filterFAQ() aqui) ...
    function filterFAQ() {
        let input, filter, faqList, detailsElements, summaryText, i, matchCount = 0;
        input = document.getElementById('faq-search');
        filter = input.value.toUpperCase();
        faqList = document.getElementById('faq-list');
        detailsElements = faqList.getElementsByTagName('details');
        const noResultsMessage = document.getElementById('no-results-message');

        for (i = 0; i < detailsElements.length; i++) {
            summaryText = detailsElements[i].querySelector('summary').textContent;
            
            if (summaryText.toUpperCase().indexOf(filter) > -1) {
                detailsElements[i].style.display = "";
                matchCount++;
            } else {
                detailsElements[i].style.display = "none";
            }
        }

        if (matchCount === 0 && filter.length > 0) {
            noResultsMessage.style.display = "block";
        } else {
            noResultsMessage.style.display = "none";
        }
    }
    </script>
    @endsection