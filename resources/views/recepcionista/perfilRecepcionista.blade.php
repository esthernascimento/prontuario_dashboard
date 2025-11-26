@extends('recepcionista.templates.recTemplate')

@section('content')

<link rel="stylesheet" href="{{ url('/css/recepcionista/perfilRecepcionista.css') }}">

<div class="cadastrar-container">
    <div class="cadastrar-header">
        <i class="bi bi-person-fill"></i>
        <h1>Meu Perfil (Recepcionista)</h1>
    </div>

    <form action="{{ route('recepcionista.atualizarPerfil') }}" method="POST" enctype="multipart/form-data"> 
        @csrf
        
        @if (session('success'))
            <div class="alert alert-success mt-3">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <div class="foto-upload-container">
            @php $recepcionista = auth()->guard('recepcionista')->user(); @endphp 

            <label for="foto" class="foto-upload-label">

                <div class="box-foto">
                    <img id="preview-img"
                         src="{{ $recepcionista->foto ? asset('storage/' . $recepcionista->foto) : asset('img/usuario-de-perfil.png') }}" 
                         alt="Foto atual">
                </div>

                <div class="overlay">
                    <i class="bi bi-camera"></i>
                    <span>Alterar Foto</span>
                </div>
            </label>
            
            <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
        </div>

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
function previewFoto(event) {
    const input = event.target;
    const preview = document.getElementById('preview-img');
    
    if (input.files && input.files[0] && preview) { 
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        if (!preview) {
             console.error("Elemento 'preview-img' não encontrado.");
        }
    }
}

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