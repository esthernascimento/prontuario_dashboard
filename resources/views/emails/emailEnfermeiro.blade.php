<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acesso de Enfermeiro — Prontuário+</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; }
        .container { background: #fff; border-radius: 10px; padding: 20px; max-width: 500px; margin: auto; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { color: #007bff; }
        p { color: #333; }
        .senha { background: #e9ecef; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold; font-size: 18px; }
        .footer { margin-top: 20px; font-size: 13px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Olá, {{ $usuario->nomeUsuario }} 👋</h2>
        <p>Você foi cadastrado como <strong>enfermeiro(a)</strong> no sistema <strong>Prontuário+</strong>.</p>
        <p>Use as credenciais abaixo para acessar o sistema:</p>

        <p><strong>E-mail:</strong> {{ $usuario->emailUsuario }}</p>
        <p><strong>Senha temporária:</strong></p>
        <div class="senha">{{ $senhaTemporaria }}</div>

        <p>Por segurança, você deverá alterar essa senha no seu primeiro login.</p>

        <p>Atenciosamente,<br><strong>Equipe Prontuário+</strong></p>

        <div class="footer">
            Este é um e-mail automático. Não responda.
        </div>
    </div>
</body>
</html>
