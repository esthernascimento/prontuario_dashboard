<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acesso ao sistema Prontuário+</title>
</head>
<body>
    <h2>Olá, {{ $unidade->nomeUnidade }}!</h2>
    <p>Seu cadastro no sistema <strong>Prontuário+</strong> foi realizado com sucesso.</p>
    <p>Segue abaixo sua senha de acesso temporária:</p>
    <p><strong>Senha:</strong> {{ $senhaTemporaria }}</p>
    <p>Recomendamos que você altere essa senha após o primeiro login.</p>
    <br>
    <p>Atenciosamente,<br>Equipe Prontuário+</p>
</body>
</html>
