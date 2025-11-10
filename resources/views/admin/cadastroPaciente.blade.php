<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+ | Pré-Cadastro Paciente</title>
  <link rel="stylesheet" href="{{ url('/css/admin/loginAdm.css') }}">
  <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
  <main class="main-container">

    <!-- Lado Esquerdo -->
    <div class="left-side">
      <img src="{{ asset('img/adm-logo2.png') }}" alt="Logo ilustrativa">
    </div>

    <div class="right-side">
      <div class="login-content fade-in">

        <h2>Pré-Cadastro de Paciente</h2>

        <form class="cads-card" id="cadastroForm">
          @csrf

          <div id="alertMessage" class="alert-error" style="display: none;"></div>
          <div id="successMessage" class="alert-success" style="display: none;"></div>

          <div class="input-group">
            <label for="nomePaciente">Nome</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user icon-left"></i>
              <input type="text" id="nomePaciente" name="nomePaciente" required />
            </div>
          </div>

          <div class="input-group">
            <label for="cpfPaciente">CPF</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-card icon-left"></i>
              <input type="text" id="cpfPaciente" name="cpfPaciente" required />
            </div>
          </div>

          <div class="input-group">
            <label for="dataNascPaciente">Data de Nascimento</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-calendar-days icon-left"></i>
              <input type="date" id="dataNascPaciente" name="dataNascPaciente" required />
            </div>
          </div>

          <div class="input-group">
            <label for="cartaoSusPaciente">Cartão SUS</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-badge icon-left"></i>
              <input type="text" id="cartaoSusPaciente" name="cartaoSusPaciente" maxlength="15" required />
            </div>
          </div>



          <div class="input-group" style="margin-top: 15px;">
            <label for="statusPaciente" style="display: flex; align-items: center; gap: 10px;">
              <input type="checkbox" id="statusPaciente" checked />
              <span>Paciente Ativo</span>
            </label>
          </div>

          <button type="submit" class="btn-login">CADASTRAR</button>
        </form>
      </div>
    </div>

  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {


      function limparInput(value) {
        return value.replace(/\D/g, "");
      }

      function mascaraCpf(cpf) {
        cpf = limparInput(cpf);
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        return cpf;
      }

      function validarCPF(cpf) {
        const cpfLimpo = limparInput(cpf);

        if (cpfLimpo.length !== 11) return false;
        if (cpfLimpo === cpfLimpo[0].repeat(11)) return false;

        let soma = 0;
        let resto;

        for (let i = 0; i < 9; i++) {
          soma += parseInt(cpfLimpo[i]) * (10 - i);
        }
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpfLimpo[9])) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
          soma += parseInt(cpfLimpo[i]) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpfLimpo[10])) return false;

        return true;
      }


      function validarCartaoSus(cartao) {
        const cartaoLimpo = limparInput(cartao);
        return cartaoLimpo.length === 15;
      }


      const inputs = document.querySelectorAll(".input-wrapper input");
      const cpfInput = document.getElementById('cpfPaciente');
      const susInput = document.getElementById('cartaoSusPaciente');
      const form = document.getElementById('cadastroForm');
      const alertMessage = document.getElementById('alertMessage');
      const successMessage = document.getElementById('successMessage');

      inputs.forEach(input => {
        input.addEventListener("focus", () => {
          input.parentElement.classList.add("focused");
          input.parentElement.classList.remove("input-invalid");
        });
        input.addEventListener("blur", () => {
          if (input.value === "") {
            input.parentElement.classList.remove("focused");
          }
        });
      });

      cpfInput.addEventListener('input', (event) => {
        event.target.value = mascaraCpf(event.target.value);
      });

      susInput.addEventListener('input', (event) => {
        event.target.value = limparInput(event.target.value);
      });

      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const cpfValue = cpfInput.value;
        const susValue = susInput.value;

        if (!validarCPF(cpfValue)) {
          alertMessage.textContent = 'Por favor, insira um CPF válido.';
          alertMessage.style.display = 'block';
          cpfInput.parentElement.classList.add("input-invalid");
          cpfInput.focus();
          return;
        }

        if (!validarCartaoSus(susValue)) {
          alertMessage.textContent = 'O Cartão SUS deve conter exatamente 15 dígitos.';
          alertMessage.style.display = 'block';
          susInput.parentElement.classList.add("input-invalid");
          susInput.focus();
          return;
        }

        alertMessage.style.display = 'none';
        cpfInput.parentElement.classList.remove("input-invalid");
        susInput.parentElement.classList.remove("input-invalid");

        submitBtn.disabled = true;
        submitBtn.textContent = 'CADASTRANDO...';
        successMessage.style.display = 'none';

        try {
          const formData = {
            nomePaciente: document.getElementById('nomePaciente').value,
            cpfPaciente: limparInput(cpfValue),
            dataNascPaciente: document.getElementById('dataNascPaciente').value,
            cartaoSusPaciente: limparInput(susValue),
            statusPaciente: document.getElementById('statusPaciente').checked,
            _token: '{{ csrf_token() }}'
          };

          console.log('Dados enviados:', formData);

          const response = await fetch('/api/pacientes', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
          });

          const result = await response.json();

          if (response.ok) {
            successMessage.textContent = 'Paciente cadastrado com sucesso!';
            successMessage.style.display = 'block';

            this.reset();
            document.getElementById('statusPaciente').checked = true;

            setTimeout(() => {
              window.location.href = "{{ route('admin.pacientes.index') }}";
            }, 2000);

          } else {
            if (result.errors) {
              const errorMessages = [];
              for (const field in result.errors) {
                errorMessages.push(result.errors[field].join(', '));
              }
              throw new Error(errorMessages.join('; '));
            } else {
              throw new Error(result.message || 'Erro ao cadastrar paciente');
            }
          }

        } catch (error) {
          alertMessage.textContent = error.message || 'Erro ao conectar com o servidor';
          alertMessage.style.display = 'block';
          console.error('Erro:', error);
        } finally {
          submitBtn.disabled = false;
          submitBtn.textContent = 'CADASTRAR';
        }
      });
    });
  </script>
  <style>
    .alert-success {
      background: #304bff47;
      color: #304bff;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      border: 1px solid #304bff47;
    }

    .alert-error {
      background: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      border: 1px solid #f5c6cb;
    }
  </style>
</body>

</html>