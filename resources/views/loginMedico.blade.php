<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Importante para o AJAX -->
    <title>Prontuário+</title>

    <link rel="stylesheet" href="{{url('/css/loginMedico.css')}}">
    <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />

    <style>
        /* Estilo para a mensagem de notificação */
        #notification {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            display: none; /* Começa escondida */
        }
        #notification.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <main class="main-container">
        <div class="logo-area">
            <img src="{{asset('img/medico-logo2.png')}}" class="logo">
        </div>

        <div class="login-area">
            <!-- Damos um ID ao formulário para o JS encontrá-lo -->
            <form class="login-card" id="medico-login-form">
                <h2>Médico(a) Login</h2>

                <!-- Div para a mensagem de notificação -->
                <div id="notification"></div>

                <label for="crm">CRM</label>
                <input type="text" id="crm" name="crm" required />
            
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required />

                <!-- CAMPO DE ESPECIALIDADE (COMEÇA ESCONDIDO) -->
                <div id="especialidade-wrapper" style="display: none;">
                    <label for="especialidade">Especialidade</label>
                    <input type="text" id="especialidade" name="especialidade" />
                </div>
            
                <button class="button" type="submit" id="submit-button">ENTRAR</button>
            
                <a href="{{url('/cadastroMedico')}}">Não tem cadastro? <strong>Clique aqui</strong></a>
            </form>
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
    
    // Variável para guardar o ID do usuário se o perfil estiver incompleto
    let incompleteUserId = null;

    form.addEventListener('submit', async function (event) {
        event.preventDefault(); // Impede o envio tradicional do formulário

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let url = '/api/medico/login/check';
        let body = {
            crm: crmInput.value,
            password: senhaInput.value
        };

        // Se estamos na etapa de completar o perfil, mudamos a URL e o corpo da requisição
        if (incompleteUserId) {
            url = '/api/medico/profile/complete';
            body = {
                user_id: incompleteUserId,
                especialidade: especialidadeInput.value
            };
        }

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

            const data = await response.json();

            if (response.ok) {
                if (data.redirect) {
                    // Perfil completo, redireciona
                    window.location.href = data.redirect;
                } else if (data.complete_profile) {
                    // Perfil incompleto, mostra o campo de especialidade
                    notification.textContent = data.message;
                    notification.className = '';
                    notification.style.display = 'block';
                    
                    especialidadeWrapper.style.display = 'block';
                    especialidadeInput.required = true;
                    senhaInput.disabled = true; // Desabilita campo de senha
                    crmInput.disabled = true; // Desabilita campo de CRM
                    
                    submitButton.textContent = 'FINALIZAR CADASTRO';
                    incompleteUserId = data.user_id; // Guarda o ID para o próximo passo
                }
            } else {
                // Erro de login (CRM/Senha inválidos)
                notification.textContent = data.message || 'Ocorreu um erro.';
                notification.className = 'error';
                notification.style.display = 'block';
            }

        } catch (error) {
            console.error('Erro na requisição:', error);
            notification.textContent = 'Erro de conexão. Tente novamente.';
            notification.className = 'error';
            notification.style.display = 'block';
        }
    });
});
</script>

</body>
</html>
