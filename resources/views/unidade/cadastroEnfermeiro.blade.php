<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+ | Cadastro de Enfermeiro(a)</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/unidade/cadastroEnfermeiro.css') }}">
  <link rel="shortcut icon" href="{{ asset('img/logo-azul.png') }}" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>
  <main class="main-container">

    <div class="left-side">
      <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo ilustrativa">
    </div>

    <div class="right-side">
      <div class="login-content">

        <h2>Cadastro de Enfermeiro(a)</h2>

        <form id="cadastroEnfermeiroForm">
          @csrf

          <div class="input-group">
            <label for="name">Nome completo</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user icon-left"></i>
              <input type="text" id="name" name="nomeEnfermeiro" required />
            </div>
          </div>

          <div class="input-group">
            <label for="corem">COREN</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-card icon-left"></i>
              <input type="text" id="corem" name="corenEnfermeiro" required />
            </div>
          </div>

          <div class="input-group">
            <label for="genero">Gênero</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-venus-mars icon-left"></i>
              <select name="genero" required>
                <option value="">Selecione</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
                <option value="Outro">Outro</option>
              </select>
            </div>
          </div>

          <div class="input-group">
            <label for="email">E-mail</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-envelope icon-left"></i>
              <input type="email" id="email" name="emailEnfermeiro" required />
            </div>
          </div>

          <div class="input-group">
            <label for="unidade">Unidade de Trabalho</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-hospital icon-left"></i>
              @if($unidadeLogada)
              <input
                type="text"
                id="unidade"
                name="unidade"
                value="{{ $unidadeLogada->nomeUnidade }}"
                readonly
                style="background-color: #f0f0f0; color: #666; cursor: not-allowed;" />
              <input type="hidden" name="unidade_id" value="{{ $unidadeLogada->idUnidadePK }}">
              @else
              <input
                type="text"
                value="Nenhuma unidade encontrada"
                readonly
                style="background-color: #f0f0f0; color: #999; cursor: not-allowed;" />
              @endif
            </div>
          </div>

          <button type="submit">CADASTRAR</button>
          <div id="form-messages" style="display:none;"></div>
        </form>

      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('cadastroEnfermeiroForm');
      const button = form.querySelector('button[type="submit"]');
      const messagesDiv = document.getElementById('form-messages');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);

        button.disabled = true;
        button.textContent = 'Cadastrando...';
        messagesDiv.style.display = 'none';
        messagesDiv.textContent = '';
        messagesDiv.classList.remove('success', 'error');

        fetch("{{ route('unidade.enfermeiro.register') }}", {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: formData
          })
          .then(response => {
            if (!response.ok) throw response;
            return response.json();
          })
          .then(result => {
            messagesDiv.textContent = result.message || 'Enfermeiro(a) cadastrado com sucesso!';
            messagesDiv.classList.add('success');
            messagesDiv.style.display = 'block';
            form.reset();

            setTimeout(() => {
              window.location.href = "{{ route('unidade.manutencaoEnfermeiro') }}";
            }, 2000);
          })
          .catch(async error => {
            let errorText = 'Erro ao cadastrar.';
            try {
              const errData = await error.json();
              if (errData.errors) {
                errorText = Object.values(errData.errors).flat().join(' ');
              } else if (errData.message) {
                errorText = errData.message;
              }
            } catch (e) {
              errorText = 'Erro inesperado. Tente novamente.';
            }

            messagesDiv.textContent = errorText;
            messagesDiv.classList.add('error');
            messagesDiv.style.display = 'block';
            button.disabled = false;
            button.textContent = 'CADASTRAR';
          });
      });
    });
  </script>
</body>

</html>