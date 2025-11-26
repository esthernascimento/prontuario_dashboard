@extends('recepcionista.templates.recTemplate')

@section('content')

<link rel="stylesheet" href="{{ url('/css/recepcionista/perfilRecepcionista.css') }}">

<div class="cadastrar-container">
    <div class="cadastrar-header">
        <i class="bi bi-person-fill"></i>
        <h1>Meu Perfil (Recepcionista)</h1>
    </div>

    <form action="{{ route('recepcionista.atualizarPerfil') }}" method="POST">
        @csrf
        
        {{-- Mensagens de Sucesso --}}
        @if (session('success'))
            <div class="alert alert-success mt-3">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        
        {{-- Mensagens de Erro --}}
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <div class="input-group">
            <label for="nomeRecepcionista">Nome:</label>
            <input type="text" name="nomeRecepcionista" id="nomeRecepcionista" value="{{ old('nomeRecepcionista', $recepcionista->nomeRecepcionista) }}" required>
        </div>

        <div class="input-group">
            <label for="emailRecepcionista">E-mail:</label>
            <input type="email" name="emailRecepcionista" id="emailRecepcionista" value="{{ old('emailRecepcionista', $recepcionista->emailRecepcionista) }}" required>
        </div>

        <div class="button-group">
            <button type="submit" class="save-button">
                <i class="bi bi-check-circle"></i> Salvar Alterações
            </button>
            
            <a href="{{ route('recepcionista.seguranca') }}" class="btn-trocar-senha">
                <i class="bi bi-key-fill"></i> Trocar Senha
            </a>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
// ===================================================
// AUTO-HIDE DE ALERTAS
// ===================================================
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endsection