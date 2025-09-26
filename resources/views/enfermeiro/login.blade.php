<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Prontuário+ - Login Enfermeiro</title>
    <link rel="stylesheet" href="{{ url('/css/enfermeiro/loginEnfermeiro.css') }}">
</head>
<body>
<main class="main-container">
    <div class="logo-area">
        <img src="{{ asset('img/enfermeiro-logo2.png') }}" class="logo">
    </div>

    <div class="login-area">
        <form action="{{ route('enfermeiro.login.submit') }}" method="POST">
            @csrf
            <h2>Enfermeiro(a) Login</h2>

            @if ($errors->any())
                <div class="error-messages">
                    @foreach ($errors->all() as $error)
                        <p style="color:red">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <label for="corem">COREM</label>
            <input type="text" id="corem" name="corem" value="{{ old('corem') }}" required />

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required />

            <button class="button" type="submit">ENTRAR</button>
        </form>
    </div>
</main>
</body>
</html>
