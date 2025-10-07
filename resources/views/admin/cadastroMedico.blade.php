<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prontuário+ | Cadastro Médico</title>

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

                    <!-- CAMPO ADICIONADO PARA AS UNIDADES -->
                    <div class="input-group">
                        <label for="unidades">Unidades de Trabalho (segure Ctrl/Cmd para mais de uma)</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-hospital icon-left"></i>
                            <select name="unidades[]" id="unidades" multiple style="height: 100px; padding-left: 35px; width: 100%; color: #333; background-color: #f0f0f0; border: 1px solid #ccc;">
                                @forelse($unidades as $unidade)
                                    <option value="{{ $unidade->idUnidadePK }}">{{ $unidade->nomeUnidade }}</option>
                                @empty
                                    <option disabled>Nenhuma unidade cadastrada. Crie uma primeiro.</option>
                                @endforelse
                            </select>
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
            const messagesDiv = document.getElementById('form-messages');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // CORREÇÃO CRUCIAL AQUI:
            // O método Object.fromEntries() não lida bem com campos de seleção múltipla.
            // Vamos construir o nosso objeto de dados manualmente para garantir que o array 'unidades' é enviado corretamente.
            const data = {};
            // Usamos o método getAll() para obter todos os valores do campo de seleção múltipla
            data.unidades = formData.getAll('unidades[]');
            
            // Agora, adicionamos os outros campos
            formData.forEach((value, key) => {
                // Adicionamos apenas se não for o campo de unidades (para não duplicar)
                if (!key.endsWith('[]')) {
                    data[key] = value;
                }
            });

            button.disabled = true;
            button.textContent = 'Cadastrando...';
            messagesDiv.style.display = 'none';

            fetch("{{ route('admin.medicos.register') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) { // Verifica se a resposta não foi bem-sucedida
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    messagesDiv.textContent = result.message + " Redirecionando...";
                    messagesDiv.classList.add('success');
                    messagesDiv.style.display = 'block';
                    form.reset();
                    setTimeout(function () {
                        window.location.href = "{{ route('admin.manutencaoMedicos') }}";
                    }, 2000);
                }
            })
            .catch(result => { // O 'catch' agora recebe o corpo do erro
                let errorText = result.message || 'Ocorreu um erro.';
                if (result.errors) {
                    errorText = Object.values(result.errors).flat().join(' ');
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
        });
    </script>
</body>
</html>