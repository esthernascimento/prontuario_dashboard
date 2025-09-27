<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+ | Admin Cadastro</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    const redirectUrl = "{{ route('admin.manutencaoEnfermeiro') }}";
  </script>
  <link rel="stylesheet" href="{{ asset('css/admin/cadastroEnfermeiro.css') }}">
  <link rel="shortcut icon" href="{{ url('img/logo-azul.png') }}" type="image/x-icon" />
</head>

<body>
  <main class="main-container">
    <div class="logo-area">
      <img src="{{ asset('img/enfermeiro-logo1.png') }}" alt="Logo Prontuário" />
    </div>

    <div class="cads-area">
      <form id="cadastroEnfermeiroForm" class="cads-card" method="POST">
        @csrf

        <h2>Enfermeiro(a) Cadastro</h2>

        <label for="name">Nome completo</label>
        <input type="text" id="name" name="nomeEnfermeiro" required />

        <label for="corem">COREM</label>
        <input type="text" id="corem" name="corenEnfermeiro" required />

        <label for="genero">Gênero</label>
        <select name="genero" required>
          <option value="">Selecione</option>
          <option value="Masculino">Masculino</option>
          <option value="Feminino">Feminino</option>
          <option value="Outro">Outro</option>
        </select>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="emailEnfermeiro" required />

        <button class="button" type="submit">CADASTRAR</button>
        <div id="form-messages" style="display:none;"></div>
      </form>
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

        fetch("{{ route('admin.enfermeiro.register') }}", {
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
            messagesDiv.textContent = result.message || 'Enfermeiro pré-cadastrado com sucesso!';
            messagesDiv.classList.add('success');
            messagesDiv.style.display = 'block';
            form.reset();

            setTimeout(() => {
              window.location.href = redirectUrl;
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
