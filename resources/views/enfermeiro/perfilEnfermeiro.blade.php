@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/perfilEnfermeiro.css') }}">

@php
// 1. Usa o guard e a variável corretos: 'enfermeiro'
// O operador de segurança (?->) evita erros se a variável $enfermeiro não for carregada.
$enfermeiro = auth()->guard('enfermeiro')->user();
@endphp

<main class="main-dashboard">
<div class="cadastrar-container">
<div class="cadastrar-header">
<i class="bi bi-person-circle icon"></i>
<h1>Perfil do Enfermeiro</h1>
</div>

    {{-- Exibe mensagens de sucesso/erro --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 2. Usa a rota correta do Enfermeiro --}}
    <form action="{{ route('enfermeiro.perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Bloco da Foto (Fundo Azul) --}}
        <div class="foto-upload-container">
            <label for="foto" class="foto-upload-label">
                <div class="box-foto">
                    {{-- Acessa a variável $enfermeiro --}}
                    <img id="preview-img"
                        src="{{ $enfermeiro?->foto ? asset('storage/fotos/' . $enfermeiro->foto) : asset('img/usuario-de-perfil.png') }}"
                        alt="Foto atual">
                </div>

                {{-- Texto e Ícone Centralizados na imagem --}}
                <div class="overlay">
                    <i class="bi bi-camera"></i>
                    <span>Alterar Foto</span>
                </div>
            </label>
            <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
        </div>

        {{-- Campos de Dados --}}
        <div class="input-group">
            <input type="text" name="nomeEnfermeiro" id="nomeEnfermeiro" placeholder="Nome Completo" 
                   value="{{ old('nomeEnfermeiro', $enfermeiro?->nomeEnfermeiro) }}" required>
        </div>

        <div class="input-group">
            <input type="text" name="corenEnfermeiro" id="corenEnfermeiro" placeholder="COREN/COREM" 
                   value="{{ $enfermeiro?->corenEnfermeiro }}" disabled title="Campo de identificação profissional não editável">
        </div>

        <div class="input-group">
            <input type="email" name="emailEnfermeiro" id="emailEnfermeiro" placeholder="E-mail" 
                   value="{{ old('emailEnfermeiro', $enfermeiro?->emailEnfermeiro) }}" required>
        </div>

        {{-- Botões de Ação --}}
        <div class="button-group">
            {{-- 3. Usa a rota correta de segurança --}}
            <a href="{{ route('enfermeiro.seguranca') }}" class="btn-trocar-senha">Trocar Senha</a> 
            <button type="submit" class="save-button">Salvar Alterações</button>
        </div>
    </form>
</div>

</main>

<script>
function previewFoto(event) {
const input = event.target;
const preview = document.getElementById('preview-img');

    if (input.files &amp;&amp; input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

</script>

@endsection