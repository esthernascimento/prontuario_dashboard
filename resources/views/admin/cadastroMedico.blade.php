<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Prontuário+ :: Cadastrar Médico</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin/cadastroMedico.css') }}">
</head>
<body>
    <main class="main-container">
        <div class="logo-area">
            <img src="{{ asset('img/medico-logo1.png') }}" alt="Logo Prontuário" />
        </div>
        <div class="cads-area">
            <form class="cads-card" id="cadastroMedicoForm">
                <h2>Médico(a) Cadastro</h2>

                <div id="form-messages" class="form-messages" style="display: none;"></div>

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
                if (result.message === 'Médico pré-cadastrado com sucesso!') {

                    messagesDiv.textContent = result.message + " Redirecionando...";
                    messagesDiv.classList.add('success');
                    messagesDiv.style.display = 'block';
                    form.reset(); 


                    setTimeout(function() {
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

                    button.disabled = false;
                    button.textContent = 'CADASTRAR';
                }
            })
            .catch(error => {
                messagesDiv.textContent = 'Ocorreu um erro de comunicação. Tente novamente.';
                messagesDiv.classList.add('error');
                messagesDiv.style.display = 'block';

                button.disabled = false;
                button.textContent = 'CADASTRAR';
            });
        });
    </script>
</body>
</html>

