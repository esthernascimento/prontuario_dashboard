<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prontuário+ :: Login Médico</title>

    <link rel="stylesheet" href="{{url('/css/loginMedico.css')}}">
    <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />

    <style>
        .notification { text-align: center; padding: 10px; margin-bottom: 15px; border-radius: 5px; display: none; }
        .notification.success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
        .notification.error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .notification.info { color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; }
    </style>
</head>
<body>
    <main class="main-container">
        <div class="logo-area">
            <img src="{{asset('img/medico-logo2.png')}}" class="logo">
        </div>

        <div class="login-area">
            <form class="login-card" id="medico-login-form" method="POST">
                <h2>Médico(a) Login</h2>

                <div id="notification" class="notification"></div>

                <div id="login-fields">
                    <label for="crm">CRM</label>
                    <input type="text" id="crm" name="crm" required />
                
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required />
                </div>

                <div id="especialidade-wrapper" style="display: none;">
                    <label for="especialidade">Especialidade</label>
                    <input type="text" id="especialidade" name="especialidade" />
                </div>
            
                <button class="button" type="submit" id="submit-button">ENTRAR</button>
            
                <a href="#">Não tem cadastro? <strong>Fale com o Administrador</strong></a>
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
                if (isCompletingProfile) {
                    notification.textContent = responseData.message;
                    notification.className = 'notification success';
                    notification.style.display = 'block';
                } else {
                    if (responseData.profile_complete) {
                        notification.textContent = 'Login realizado com sucesso!';
                        notification.className = 'notification success';
                        notification.style.display = 'block';
                        // Futuramente, redirecionar: window.location.href = responseData.redirect_url;
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
});
</script>

</body>
</html>

