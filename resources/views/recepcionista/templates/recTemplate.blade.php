<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Recepcionista | Prontuário+</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/recepcionista/acolhimento.css') }}">

    @yield('styles')
</head>

<body>

    <div class="sidebar d-flex flex-column">
        <div class="sidebar-header">
            <img src="{{ asset('img/recepcionista-logo2.png') }}" class="logo-recepcionista"
                alt="Logo Recepcionista(a)">
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('recepcionista.dashboard') }}" class="nav-link active">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Iniciar Atendimento
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('recepcionista.perfil') }}" class="nav-link">
                    <i class="bi bi-folder2-open me-2"></i>
                    Perfil
                </a>

            </li>


            <!-- (Exemplo) Você pode adicionar outras telas aqui no futuro -->
            <!--
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-people-fill me-2"></i>
                    Fila de Espera
                </a>
            </li>
            -->
        </ul>

        <hr class="text-white-50">
        <div>
            <form action="{{ route('recepcionista.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-dark w-100 logout-btn">
                    <i class="bi bi-box-arrow-left me-2"></i>
                    Sair
                </button>
            </form>
        </div>
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>