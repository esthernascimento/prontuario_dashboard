@extends('admin.templates.admTemplate')

@section('title', 'Cadastrar Nova Unidade')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/cadastroUnidade.css') }}">

<main class="main-dashboard">
    <div class="cadastrar-container">
        <div class="cadastrar-header">
            <i class="bi bi-hospital-fill icon"></i>
            <h1>Cadastrar Nova Unidade</h1>
        </div>

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.unidades.store') }}" method="POST">
            @csrf

            <div class="form-section-title">Informações da Unidade</div>

            <div class="input-group">
                <label for="nomeUnidade">Nome da Unidade</label>
                <input type="text" name="nomeUnidade" id="nomeUnidade" value="{{ old('nomeUnidade') }}" placeholder="Ex: Hospital Municipal Central" required>
            </div>

            <div class="split-group">
                <div class="input-group">
                    <label for="tipoUnidade">Tipo de Unidade</label>
                    <input type="text" name="tipoUnidade" id="tipoUnidade" value="{{ old('tipoUnidade') }}" placeholder="Ex: Hospital Geral, UBS">
                </div>
                <div class="input-group">
                    <label for="telefoneUnidade">Telefone</label>
                    <input type="text" name="telefoneUnidade" id="telefoneUnidade" value="{{ old('telefoneUnidade') }}" placeholder="(11) 99999-9999">
                </div>
            </div>

            <hr class="section-separator">
            <div class="form-section-title">Endereço</div>

            {{-- CAMPO DE CEP AGORA FICA NO TOPO --}}
            <div class="input-group">
                <label for="cepUnidade">CEP</label>
                <input type="text" name="cepUnidade" id="cepUnidade" value="{{ old('cepUnidade') }}" placeholder="Digite o CEP e aguarde" maxlength="9">
            </div>

            <div class="input-group">
                <label for="logradouroUnidade">Logradouro</label>
                <input type="text" name="logradouroUnidade" id="logradouroUnidade" value="{{ old('logradouroUnidade') }}" placeholder="Preenchido automaticamente">
            </div>

            <div class="split-group">
                <div class="input-group" style="flex: 1;">
                    <label for="numLogradouroUnidade">Número</label>
                    <input type="text" name="numLogradouroUnidade" id="numLogradouroUnidade" value="{{ old('numLogradouroUnidade') }}" placeholder="Digite o número">
                </div>
                <div class="input-group" style="flex: 2;">
                    <label for="bairroUnidade">Bairro</label>
                    <input type="text" name="bairroUnidade" id="bairroUnidade" value="{{ old('bairroUnidade') }}" placeholder="Preenchido automaticamente">
                </div>
            </div>

            <div class="split-group">
                <div class="input-group">
                    <label for="cidadeUnidade">Cidade</label>
                    <input type="text" name="cidadeUnidade" id="cidadeUnidade" value="{{ old('cidadeUnidade') }}" placeholder="Preenchido automaticamente">
                </div>
                <div class="input-group">
                    <label for="ufUnidade">UF</label>
                    <input type="text" name="ufUnidade" id="ufUnidade" value="{{ old('ufUnidade') }}" placeholder="Preenchido automaticamente" maxlength="2">
                </div>
            </div>
            
            <div class="split-group">
                 <div class="input-group">
                    <label for="estadoUnidade">Estado</label>
                    <input type="text" name="estadoUnidade" id="estadoUnidade" value="{{ old('estadoUnidade') }}" placeholder="Ex: São Paulo">
                </div>
                <div class="input-group">
                    <label for="paisUnidade">País</label>
                    <input type="text" name="paisUnidade" id="paisUnidade" value="{{ old('paisUnidade') }}" placeholder="Brasil">
                </div>
            </div>

            <button type="submit" class="save-button">Cadastrar Unidade</button>
        </form>
    </div>
</main>

{{-- SCRIPT DA API VIACEP --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cepInput = document.getElementById('cepUnidade');
        const logradouroInput = document.getElementById('logradouroUnidade');
        const bairroInput = document.getElementById('bairroUnidade');
        const cidadeInput = document.getElementById('cidadeUnidade');
        const ufInput = document.getElementById('ufUnidade');
        const numeroInput = document.getElementById('numLogradouroUnidade');

        // Adiciona um "ouvinte" que é acionado quando o utilizador sai do campo do CEP
        cepInput.addEventListener('blur', function () {
            // Limpa e formata o CEP para conter apenas números
            const cep = this.value.replace(/\D/g, '');

            // Verifica se o CEP tem o tamanho correto (8 dígitos)
            if (cep.length === 8) {
                // Mostra uma mensagem de "a carregar"
                logradouroInput.value = 'Buscando...';
                bairroInput.value = 'Buscando...';
                cidadeInput.value = 'Buscando...';
                ufInput.value = '...';

                // Faz a chamada à API do ViaCEP
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.erro) {
                            // Se o CEP não for encontrado, limpa os campos
                            alert('CEP não encontrado.');
                            logradouroInput.value = '';
                            bairroInput.value = '';
                            cidadeInput.value = '';
                            ufInput.value = '';
                        } else {
                            // Se o CEP for encontrado, preenche os campos
                            logradouroInput.value = data.logradouro;
                            bairroInput.value = data.bairro;
                            cidadeInput.value = data.localidade;
                            ufInput.value = data.uf;
                            
                            // Coloca o foco no campo de número para o utilizador preencher
                            numeroInput.focus();
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar o CEP:', error);
                        alert('Não foi possível buscar o CEP. Verifique a sua conexão.');
                        logradouroInput.value = '';
                        bairroInput.value = '';
                        cidadeInput.value = '';
                        ufInput.value = '';
                    });
            }
        });
    });
</script>

@endsection

