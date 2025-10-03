<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Prontuário+ : Pré-Cadastro Paciente</title>
    <link rel="stylesheet" href="{{ asset('css/admin/cadastroPaciente.css') }}">
</head>
<body>
    <main class="main-container">
        <div class="logo-area">
            <img src="{{ asset('img/adm-logo1.png') }}" alt="Logo Prontuário" />
        </div>

        <div class="cads-area">
            <form class="cads-card" action="/api/pacientes" method="POST">
                @csrf

                <h2>Pré-Cadastro de Paciente</h2>

                <label for="nomePaciente">Nome:</label>
                <input type="text" name="nomePaciente" id="nomePaciente" required>

                <label for="cpfPaciente">CPF:</label>
                <input type="text" name="cpfPaciente" id="cpfPaciente" required>

                <label for="dataNascPaciente">Data de Nascimento:</label>
                <input type="date" name="dataNascPaciente" id="dataNascPaciente" required>

                <label for="cartaoSusPaciente">Cartão SUS:</label>
                <input type="text" name="cartaoSusPaciente" id="cartaoSusPaciente" required>

                <!-- STATUS CORRIGIDO -->
                <label for="statusPaciente" style="display: flex; align-items: center; gap: 8px; margin: 15px 0;">
                    <input type="checkbox" name="statusPaciente" id="statusPaciente" value="1" checked>
                    <span>Paciente Ativo</span>
                </label>

                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </main>
</body>
</html>