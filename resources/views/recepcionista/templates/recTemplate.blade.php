<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Recepcionista | Prontuário+</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Nosso CSS de Acolhimento -->
    <link rel="stylesheet" href="{{ asset('css/acolhimento.css') }}">

    <!-- CSS Básico do Painel (Novo) -->
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f7f6; /* Fundo do conteúdo */
        }
        .sidebar {
            width: 260px;
            background-color: #0d6efd; /* Azul primário */
            color: white;
            padding: 1.5rem 1rem;
            flex-shrink: 0; /* Não encolher */
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.05rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }
        .sidebar .nav-link.active {
            background-color: #0b5ed7; /* Azul mais escuro */
            color: white;
            font-weight: 600;
        }
        .sidebar .nav-link:hover {
            background-color: #0c66e4;
            color: white;
        }
        .sidebar .sidebar-header {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar .logout-btn {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .main-content {
            flex-grow: 1; /* Ocupa o resto do espaço */
            padding: 2rem;
            overflow-y: auto; /* Scroll se necessário */
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- ======================= -->
    <!--     MENU LATERAL        -->
    <!-- ======================= -->
    <div class="sidebar d-flex flex-column">
        <div class="sidebar-header">
            Prontuário+
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <!-- Link que aponta para a nossa tela de acolhimento -->
                <a href="{{ route('recepcionista.dashboard') }}" class="nav-link active">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Iniciar Atendimento
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

        <!-- Botão de Logout -->
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

    <!-- ======================= -->
    <!--     CONTEÚDO DA PÁGINA   -->
    <!-- ======================= -->
    <main class="main-content">
        <!-- O conteúdo de 'create.blade.php' será injetado aqui -->
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts da página (ex: nosso JS de busca AJAX) -->
    @stack('scripts')
</body>
</html>
