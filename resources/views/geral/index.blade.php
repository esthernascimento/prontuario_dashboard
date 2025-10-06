<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Prontuário+</title>
    <link rel="stylesheet" href="{{ asset('css/geral/index.css') }}">
</head>

<body>
    <main class="main-container">
        <!-- Conteúdo do lado esquerdo -->
        <div class="box-content">
            <h1>Seja bem-vindo<br>ao Prontuário+</h1>
            
            <div class="subtitle">SELECIONE A SUA ÁREA DE ATUAÇÃO</div>

            <div class="area-selection">
                <a href="{{ url('/loginAdm') }}" class="area-button administrador">
                    Administrador
                </a>
                <a href="{{ url('/loginMedico') }}" class="area-button medico">
                    Médico
                </a>
                <a href="{{ url('/enfermeiro/login') }}" class="area-button enfermeiro">
                    Enfermeiro
                </a>
            </div>
        </div>

        <!-- Logo do lado direito -->
        <div class="logo-container">
       
        </div>
    </main>
</body>
</html>