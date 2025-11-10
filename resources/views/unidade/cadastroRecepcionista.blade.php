<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prontuário+ | Cadastro Recepcionista</title>

    <link rel="stylesheet" href="{{ url('/css/unidade/cadastroRecepcionista.css') }}">
    <link rel="shortcut icon" href="{{url('img/logo-azul.png')}}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <main class="main-container">
        <div class="left-side">
            <img src="{{ asset('img/unidade-logo2.png') }}" alt="Logo ilustrativa">
        </div>

        <div class="right-side">
            <div class="login-content fade-in">
                <h2>Cadastro de Recepcionista</h2>
                <div id="form-messages" class="alert-error" style="display: none;"></div>
                <form id="cadastroRecepcionistaForm">
                    @csrf
                    <div class="input-group">
                        <label for="nomeRecepcionista">Nome completo</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user icon-left"></i> <!-- 🔥 Mudei o ícone -->
                            <input type="text" id="nomeRecepcionista" name="nomeRecepcionista" required />
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="emailRecepcionista">E-mail</label> <!-- 🔥 Corrigi o name -->
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope icon-left"></i>
                            <input type="email" id="emailRecepcionista" name="emailRecepcionista" required /> <!-- 🔥 Corrigi o name -->
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="senhaRecepcionista">Senha</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock icon-left"></i>
                            <input type="password" id="senhaRecepcionista" name="senhaRecepcionista" required minlength="6" />
                        </div>
                    </div>

                    <button class="btn-login" type="submit">CADASTRAR</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('cadastroRecepcionistaForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const form = event.target;
            const button = form.querySelector('button');
            const formData = new FormData(form);
            const messagesDiv = document.getElementById('form-messages');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            button.disabled = true;
            button.textContent = 'Cadastrando...';
            messagesDiv.style.display = 'none';

            fetch("{{ route('unidade.recepcionistas.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData 
            })
            .then(response => {
                if (!response.ok) { 
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(result => {
                if (result.success || result.idRecepcionistaPK) {
                    const message = result.message || "Recepcionista cadastrado com sucesso!";
                    messagesDiv.textContent = message + " Redirecionando...";
                    messagesDiv.classList.remove('error');
                    messagesDiv.classList.add('success');
                    messagesDiv.style.display = 'block';
                    form.reset();
                    setTimeout(function () {
                        window.location.href = "{{ route('unidade.manutencaoRecepcionista') }}";
                    }, 2000);
                }
            })
            .catch(error => { 
                console.error('Erro:', error);
                let errorText = error.message || 'Ocorreu um erro no cadastro.';
                if (error.errors) {
                    errorText = Object.values(error.errors).flat().join(' ');
                }
                messagesDiv.textContent = errorText;
                messagesDiv.classList.remove('success');
                messagesDiv.classList.add('error');
                messagesDiv.style.display = 'block';
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = 'CADASTRAR';
            });
        });

        const inputs = document.querySelectorAll(".input-wrapper input, .input-wrapper select");
        inputs.forEach(input => {
            input.addEventListener("focus", () => {
                input.parentElement.classList.add("focused");
            });
            input.addEventListener("blur", () => {
                if (input.value === "") {
                    input.parentElement.classList.remove("focused");
                }
            });

            if (input.value !== "") {
                input.parentElement.classList.add("focused");
            }
        });
    </script>
</body>
</html>