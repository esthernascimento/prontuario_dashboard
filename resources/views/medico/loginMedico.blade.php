<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prontuário+ :: Login Médico</title>

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ url('/css/medico/loginMedico.css') }}">
    <link rel="shortcut icon" href="{{ url('img/logo-azul.png') }}" type="image/x-icon" />
</head>

<body>
    <main class="main-container">
        <!-- Lado esquerdo (imagem) -->
        <div class="left-side">
            <img src="{{ asset('img/medico-logo2.png') }}" alt="Logo Médico">
        </div>

        <!-- Lado direito (formulário) -->
        <div class="right-side">
            <div class="login-content">
                <div class="logo">
                    <img src="{{ asset('img/icon-loginMedico.png') }}" alt="Logo Prontuário+">
                </div>

                <h2>Login Médico</h2>

                <div id="notification" class="notification"></div>

                <form id="medico-login-form" method="POST">
                    <div id="login-fields">
                        <div class="input-group">
                            <label for="crm">CRM</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-user icon-left"></i>
                                <input type="text" id="crm" name="crm" required />
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="senha">Senha</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-lock icon-left"></i>
                                <input type="password" id="senha" name="senha" required />
                                <i id="togglePassword" class="fa-solid fa-eye-slash icon-right"></i>
                            </div>
                        </div>
                    </div>

                    <div id="especialidade-wrapper" style="display: none;">
                        <div class="input-group">
                            <label for="especialidade">Especialidade</label>
                            <div class="input-wrapper">
                                <input type="text" id="especialidade" name="especialidade" />
                            </div>
                        </div>
                    </div>

                    <button class="btn-login" type="submit" id="submit-button">ENTRAR</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('medico-login-form');
            const notification = document.getElementById('notification');
            const especialidadeWrapper = document.getElementById('especialidade-wrapper');
            const submitButton = document.getElementById('submit-button');
            const crmInput = document.getElementById('crm');
            const senhaInput = document.getElementById('senha');
            const especialidadeInput = document.getElementById('especialidade');

            let isCompletingProfile = false;

            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                let url = isCompletingProfile
                    ? "{{ route('api.medico.profile.complete') }}"
                    : "{{ route('api.medico.login.check') }}";

                let body = isCompletingProfile
                    ? { crm: crmInput.value, especialidade: especialidadeInput.value }
                    : { crm: crmInput.value, senha: senhaInput.value };

                submitButton.disabled = true;
                submitButton.textContent = 'Aguarde...';
                notification.style.display = 'none';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });

                    const responseData = await response.json();

                    if (response.ok) {
                        if (responseData.profile_complete) {
                            notification.textContent = 'Login realizado com sucesso!';
                            notification.className = 'notification success';
                            notification.style.display = 'block';
                            window.location.href = responseData.redirect_url;
                        } else {
                            isCompletingProfile = true;
                            notification.textContent = 'Perfil incompleto. Informe sua especialidade para continuar.';
                            notification.className = 'notification info';
                            notification.style.display = 'block';

                            document.getElementById('login-fields').style.display = 'none';
                            especialidadeWrapper.style.display = 'block';
                            especialidadeInput.required = true;
                            crmInput.readOnly = true;

                            submitButton.textContent = 'FINALIZAR CADASTRO';
                            submitButton.disabled = false;
                        }
                    } else {
                        let errorMessage = responseData.message || 'Ocorreu um erro.';
                        if (responseData.errors) {
                            errorMessage = Object.values(responseData.errors)[0][0];
                        }
                        notification.textContent = errorMessage;
                        notification.className = 'notification error';
                        notification.style.display = 'block';
                        submitButton.disabled = false;
                        submitButton.textContent = isCompletingProfile ? 'FINALIZAR CADASTRO' : 'ENTRAR';
                    }
                } catch (error) {
                    notification.textContent = 'Erro de conexão. Tente novamente.';
                    notification.className = 'notification error';
                    notification.style.display = 'block';
                    submitButton.disabled = false;
                    submitButton.textContent = isCompletingProfile ? 'FINALIZAR CADASTRO' : 'ENTRAR';
                }
            });

            // Mostrar/ocultar senha
            const togglePassword = document.getElementById("togglePassword");
            if (togglePassword && senhaInput) {
                togglePassword.addEventListener("click", () => {
                    const isPassword = senhaInput.type === "password";
                    senhaInput.type = isPassword ? "text" : "password";
                    togglePassword.classList.toggle("fa-eye");
                    togglePassword.classList.toggle("fa-eye-slash");
                });
            }

            // Efeito de foco nos inputs
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
        });
    </script>
</body>
</html>
