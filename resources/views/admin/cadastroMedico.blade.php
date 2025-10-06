<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Prontuário+ | Cadastro Médico</title>

  <!-- Estilo base herdado do login -->
  <link rel="stylesheet" href="{{ url('/css/admin/cadastroMedico.css') }}">
  <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
  <main class="main-container">


    <div class="left-side">
      <img src="{{ asset('img/medico-logo2.png') }}" alt="Logo Médico" />
    </div>

    <div class="right-side">
      <div class="login-content fade-in">
      

        <h2>Cadastro de Médico</h2>

        <div id="form-messages" class="alert-error" style="display: none;"></div>

        <form id="cadastroMedicoForm">
          @csrf

          <div class="input-group">
            <label for="nomeMedico">Nome completo</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user-doctor icon-left"></i>
              <input type="text" id="nomeMedico" name="nomeMedico" required />
            </div>
          </div>

          <div class="input-group">
            <label for="crmMedico">CRM</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-card icon-left"></i>
              <input type="text" id="crmMedico" name="crmMedico" required />
            </div>
          </div>

          <div class="input-group">
            <label for="emailUsuario">E-mail</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-envelope icon-left"></i>
              <input type="email" id="emailUsuario" name="emailUsuario" required />
            </div>
          </div>



          <button class="btn-login" type="submit">CADASTRAR</button>
        </form>
      </div>
    </div>
  </main>

  <script>
    document.getElementById('cadastroMedicoForm').addEventListener('submit', function (event) {
      event.preventDefault();

      const form = event.target;
      const button = form.querySelector('button');
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());
      const messagesDiv = document.getElementById('form-messages');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      button.disabled = true;
      button.textContent = 'Cadastrando...';

      messagesDiv.style.display = 'none';
      messagesDiv.textContent = '';
      messagesDiv.classList.remove('success', 'error');

      fetch("{{ route('admin.medicos.register') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          messagesDiv.textContent = result.message + " Redirecionando...";
          messagesDiv.classList.add('success');
          messagesDiv.style.display = 'block';
          form.reset();

          setTimeout(function () {
            window.location.href = "{{ route('admin.manutencaoMedicos') }}";
          }, 2000);
        } else {
          let errorText = result.message || 'Ocorreu um erro.';
          if (result.errors) {
            errorText = Object.values(result.errors).flat().join(' ');
          }
          messagesDiv.textContent = errorText;
          messagesDiv.classList.add('error');
          messagesDiv.style.display = 'block';
        }

        button.disabled = false;
        button.textContent = 'CADASTRAR';
      })
      .catch(error => {
        messagesDiv.textContent = 'Ocorreu um erro de comunicação. Tente novamente.';
        messagesDiv.classList.add('error');
        messagesDiv.style.display = 'block';

        button.disabled = false;
        button.textContent = 'CADASTRAR';
      });
    });

    const inputs = document.querySelectorAll(".input-wrapper input");

    inputs.forEach(input => {
      input.addEventListener("focus", () => {
        input.parentElement.classList.add("focused");
      });
      input.addEventListener("blur", () => {
        if (input.value === "") {
          input.parentElement.classList.remove("focused");
        }
      });
    });
  </script>
</body>
</html>
