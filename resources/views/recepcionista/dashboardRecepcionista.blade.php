@extends('recepcionista.templates.recTemplate')

@section('content')

<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-10 offset-md-1"> 
            
            {{-- Banner de Boas-vindas --}}
            <div class="welcome-banner-rec mb-4">
                <div class="banner-left-rec">
                    <img src="{{ asset('img/recepcionista-logo2.png') }}" class="banner-logo-rec" alt="Logo">
                </div>
                <div class="banner-center-rec">
                    <h2>Olá, <span class="name-rec">{{ Auth::user()->name ?? 'Recepcionista' }}</span>! 👋</h2>
                    <p>Inicie um novo atendimento preenchendo os dados abaixo</p>
                </div>
                <div class="banner-right-rec">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
            </div>

            {{-- Mensagens de Feedback --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm mb-4 alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger shadow-sm mb-4 alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Ops! Algo deu errado:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Progress Indicator --}}
            <div class="progress-container mb-4">
                <div class="progress-step active" id="step-1">
                    <div class="step-number">1</div>
                    <div class="step-label">Paciente</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step" id="step-2">
                    <div class="step-number">2</div>
                    <div class="step-label">Unidade</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step" id="step-3">
                    <div class="step-number">3</div>
                    <div class="step-label">Queixa</div>
                </div>
            </div>

            <form action="{{ route('recepcionista.acolhimento.store') }}" method="POST" id="formAcolhimento">
                @csrf

                {{-- Card 1: Paciente --}}
                <div class="card mb-4 shadow-sm step-card" data-step="1">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>
                            1. Identificação do Paciente
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3" id="busca-container">
                            <label for="busca_paciente" class="form-label fs-5">
                                <i class="bi bi-search me-2"></i>
                                Buscar Paciente (Nome, CPF ou Cartão SUS):
                            </label>
                            
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       id="busca_paciente" 
                                       class="form-control" 
                                       placeholder="Digite 3 ou mais letras para buscar..." 
                                       autocomplete="off">
                            </div>
                            
                            <div id="resultados_busca_paciente" class="list-group mt-2"></div>
                        </div>

                        <div id="paciente_selecionado_info" class="alert alert-info" style="display: none;">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2 fs-4"></i>
                                <strong class="fs-5">Paciente Selecionado</strong>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong><i class="bi bi-person me-2"></i>Nome:</strong> 
                                    <span id="info_nome"></span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong><i class="bi bi-calendar-event me-2"></i>Data Nasc:</strong> 
                                    <span id="info_data_nasc"></span>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <strong><i class="bi bi-credit-card me-2"></i>CPF:</strong> 
                                    <span id="info_cpf"></span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btnAlterarPaciente">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Alterar Paciente
                            </button>
                        </div>

                        <input type="hidden" id="paciente_id" name="paciente_id">
                    </div>
                </div>

                {{-- Cards 2 e 3 aparecem após selecionar paciente --}}
                <div id="passo_2_acolhimento" style="display: none;">
                    
                    {{-- Card 2: Unidade --}}
                    <div class="card mb-4 shadow-sm step-card" data-step="2">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="bi bi-hospital me-2"></i>
                                2. Unidade de Atendimento
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="unidade_id" class="form-label fs-5">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    Selecione a Unidade:
                                </label>
                                <select name="unidade_id" 
                                        id="unidade_id" 
                                        class="form-select form-select-lg" 
                                        required>
                                    <option value="" disabled selected>Escolha uma unidade...</option>
                                    @foreach($unidades as $unidade)
                                        <option value="{{ $unidade->idUnidadePK }}">
                                            {{ $unidade->nomeUnidade }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Selecione a unidade onde o atendimento será realizado
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Queixa Principal --}}
                    <div class="card mb-4 shadow-sm step-card" data-step="3">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="bi bi-clipboard2-pulse me-2"></i>
                                3. Queixa Principal
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="queixa_principal" class="form-label fs-5">
                                    <i class="bi bi-chat-left-text me-2"></i>
                                    Queixa Principal / Motivo da Visita:
                                </label>
                                <textarea name="queixa_principal" 
                                          id="queixa_principal" 
                                          class="form-control" 
                                          rows="5" 
                                          placeholder="Descreva detalhadamente o que o paciente está sentindo ou o motivo da consulta..." 
                                          required></textarea>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Seja o mais específico possível para auxiliar a equipe de triagem
                                </div>
                                <div class="char-counter mt-2 text-end">
                                    <small class="text-muted">
                                        <span id="char_count">0</span> caracteres
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botão Final --}}
                    <div class="text-center mt-4 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5 py-3 submit-btn">
                            <i class="bi bi-send-check me-2"></i>
                            Salvar e Encaminhar para Triagem
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts') 
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos do DOM
    const campoBusca = document.getElementById('busca_paciente');
    const resultadosDiv = document.getElementById('resultados_busca_paciente');
    const pacienteIdInput = document.getElementById('paciente_id');
    const infoPacienteDiv = document.getElementById('paciente_selecionado_info');
    const passo2Div = document.getElementById('passo_2_acolhimento');
    const btnAlterarPaciente = document.getElementById('btnAlterarPaciente');
    const queixaTextarea = document.getElementById('queixa_principal');
    const charCount = document.getElementById('char_count');
    const formAcolhimento = document.getElementById('formAcolhimento');
    const unidadeSelect = document.getElementById('unidade_id');
    
    // Progress Steps
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const progressLines = document.querySelectorAll('.progress-line');

    const urlBusca = "{{ route('recepcionista.pacientes.buscar') }}";

    let timerDebounce;

    // Debounce Function
    function debounce(func, delay) {
        return function(...args) {
            clearTimeout(timerDebounce);
            timerDebounce = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Buscar Pacientes
    async function buscarPacientes(termo) {
        if (termo.length < 3) { 
            resultadosDiv.innerHTML = '';
            resultadosDiv.style.display = 'none';
            return;
        }

        resultadosDiv.style.display = 'block';
        resultadosDiv.innerHTML = '<div class="list-group-item"><i class="bi bi-hourglass-split me-2"></i>Buscando...</div>';

        try {
            const response = await fetch(`${urlBusca}?term=${encodeURIComponent(termo)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Falha na requisição: ' + response.statusText);
            }

            const pacientes = await response.json();
            mostrarResultados(pacientes);

        } catch (error) {
            console.error('Erro ao buscar pacientes:', error);
            resultadosDiv.innerHTML = `
                <div class="list-group-item list-group-item-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Erro ao buscar. Tente novamente.
                </div>`;
        }
    }

    // Mostrar Resultados
    function mostrarResultados(pacientes) {
        resultadosDiv.innerHTML = '';

        if (pacientes.length === 0) {
            resultadosDiv.innerHTML = `
                <div class="list-group-item">
                    <i class="bi bi-info-circle me-2"></i>
                    Nenhum paciente encontrado.
                </div>`;
            return;
        }

        pacientes.forEach(paciente => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action';
            
            item.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-circle me-3 fs-3 text-muted"></i>
                    <div class="flex-grow-1">
                        <strong>${paciente.nomePaciente}</strong><br>
                        <small class="text-muted">
                            <i class="bi bi-credit-card me-1"></i>
                            CPF: ${paciente.cpfPaciente || 'Não informado'}
                        </small>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            `;
            
            item.dataset.id = paciente.idPaciente;
            item.dataset.nome = paciente.nomePaciente;
            item.dataset.cpf = paciente.cpfPaciente || 'Não informado';
            item.dataset.nasc = paciente.dataNascPaciente || 'Não informada';
            
            item.addEventListener('click', selecionarPaciente);
            
            resultadosDiv.appendChild(item);
        });
    }

    // Selecionar Paciente
    function selecionarPaciente(event) {
        event.preventDefault();
        const itemSelecionado = event.currentTarget;

        pacienteIdInput.value = itemSelecionado.dataset.id;

        document.getElementById('info_nome').textContent = itemSelecionado.dataset.nome;
        document.getElementById('info_data_nasc').textContent = itemSelecionado.dataset.nasc;
        document.getElementById('info_cpf').textContent = itemSelecionado.dataset.cpf;
        
        infoPacienteDiv.style.display = 'block';
        infoPacienteDiv.style.animation = 'fadeIn 0.5s ease';

        resultadosDiv.innerHTML = '';
        resultadosDiv.style.display = 'none';
        campoBusca.value = '';
        campoBusca.disabled = true;

        // Update progress
        step1.classList.add('completed');
        step2.classList.add('active');
        progressLines[0].classList.add('completed');

        passo2Div.style.display = 'block';
        
        // Scroll suave até o próximo card
        setTimeout(() => {
            document.querySelector('.step-card[data-step="2"]').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }, 100);
    }

    // Alterar Paciente
    btnAlterarPaciente.addEventListener('click', function() {
        pacienteIdInput.value = '';
        infoPacienteDiv.style.display = 'none';
        passo2Div.style.display = 'none';
        campoBusca.disabled = false;
        campoBusca.focus();
        
        // Reset progress
        step1.classList.remove('completed');
        step2.classList.remove('active', 'completed');
        step3.classList.remove('active', 'completed');
        progressLines.forEach(line => line.classList.remove('completed'));
        step1.classList.add('active');
    });

    // Update progress when unit is selected
    unidadeSelect.addEventListener('change', function() {
        if (this.value) {
            step2.classList.add('completed');
            step3.classList.add('active');
            progressLines[1].classList.add('completed');
            
            setTimeout(() => {
                document.querySelector('.step-card[data-step="3"]').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }, 100);
        }
    });

    // Character Counter
    if (queixaTextarea && charCount) {
        queixaTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // Form Submit with Loading State
    formAcolhimento.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
    });

    // Event Listener para busca
    campoBusca.addEventListener('keyup', debounce(function(e) {
        buscarPacientes(e.target.value);
    }, 300));

    // Prevenir resubmissão no F5
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});
</script>
@endpush