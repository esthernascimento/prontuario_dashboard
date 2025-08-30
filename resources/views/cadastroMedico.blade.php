<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Importante para a API -->
    <title>Cadastro de Médico - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/cadastroMedico.css') }}">
    <style>
        /* Estilo para a mensagem de notificação */
        #notification {
            text-align: center; padding: 10px; margin-bottom: 15px; border-radius: 5px;
            display: none; /* Começa escondida */
        }
        #notification.success {
            color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb;
        }
        #notification.error {
            color: #721c24; background-color: #f8d7da; border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <main class="main-container">
        <div class="logo-area">
            <img src="{{ asset('img/medico-logo1.png') }}" alt="Logo Prontuário" />
        </div>
        <div class="cads-area">
            <!-- Damos um ID ao formulário para o JS -->
            <form class="cads-card" id="cadastro-medico-form">
                <h2>Médico(a) Cadastro</h2>

                <!-- Div para a mensagem de notificação -->
                <div id="notification"></div>

                <!-- Os nomes dos campos (name) foram ajustados para o que o Controller espera -->
                <label for="nomeMedico">Nome completo</label>
                <input type="text" id="nomeMedico" name="nomeMedico" required />

                <label for="crmMedico">CRM</label>
                <input type="text" id="crmMedico" name="crmMedico" required />

                <label for="emailUsuario">E-mail</label>
                <input type="email" id="emailUsuario" name="emailUsuario" required />

                <label for="senhaUsuario">Senha</label>
                <input type="password" id="senhaUsuario" name="senhaUsuario" required />

                <button class="button" type="submit">CADASTRAR</button>
            </form>
        </div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('cadastro-medico-form');
    const notification = document.getElementById('notification');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            // A URL aponta para a rota da API que criamos no AdminController
            const response = await fetch('/api/admin/register/medico', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            notification.textContent = result.message;
            notification.style.display = 'block';

            if (response.ok) {
                notification.className = 'success';
                form.reset(); // Limpa o formulário após o sucesso
            } else {
                notification.className = 'error';
                // Se houver erros de validação, você pode exibi-los aqui
                if (result.errors) {
                    let errorMessages = [result.message];
                    for (const key in result.errors) {
                        errorMessages.push(result.errors[key][0]);
                    }
                    notification.innerHTML = errorMessages.join('<br>');
                }
            }

        } catch (error) {
            notification.className = 'error';
            notification.textContent = 'Erro de conexão. Tente novamente.';
            notification.style.display = 'block';
        }
    });
});
</script>
</body>
</html>
