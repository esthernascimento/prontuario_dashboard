<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo ao Sistema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333333;
        }
        p {
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1d72b8;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Olá, {{ $usuario->nomeUsuario }}!</h2>

        <p>Seu cadastro no sistema foi criado com sucesso pelo administrador.</p>

        <p>
            Aqui estão suas credenciais de acesso temporárias:
        </p>

        <p>
            <strong>E-mail:</strong> {{ $usuario->emailUsuario }}<br>
            <strong>Senha temporária:</strong> {{ $senhaTemporaria }}
        </p>

        <p>
            Ao fazer login pela primeira vez, você será solicitado a alterar sua senha e completar seu cadastro.
        </p>

        <a class="btn" href="{{ url('/login') }}">Acessar o Sistema</a>

        <div class="footer">
            <p>Este e-mail foi enviado automaticamente pelo sistema. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>
