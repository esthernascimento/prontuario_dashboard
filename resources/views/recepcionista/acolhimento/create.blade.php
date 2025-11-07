@extends('recepcionista.templates.recTemplate')

@section('content')

<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-10 offset-md-1"> 
            
            {{-- Bloco para mostrar sucesso (quando funciona) --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            {{-- Bloco para mostrar erros de validação (o "F5 infinito") --}}
            @if($errors->any())
                <div class="alert alert-danger shadow-sm mb-4" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Ops! Algo deu errado:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="mb-4">Iniciar Novo Atendimento (Acolhimento)</h2>

            <form action="{{ route('recepcionista.acolhimento.store') }}" method="POST">
                @csrf

                {{-- Card 1: Paciente --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">1. Identificação do Paciente</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3" id="busca-container">
                            <label for="busca_paciente" class="form-label fs-5">Buscar Paciente (Nome, CPF ou Cartão SUS):</label>
                            
                            <input type="text" id="busca_paciente" class="form-control form-control-lg" placeholder="Digite 3 ou mais letras para buscar..." autocomplete="off">
                            
                            <div id="resultados_busca_paciente" class="list-group mt-2"></div>
                        </div>

                        <div id="paciente_selecionado_info" class="alert alert-info" style="display: none;">
                            <strong>Paciente:</strong> <span id="info_nome"></span><br>
                            <strong>Data Nasc:</strong> <span id="info_data_nasc"></span><br>
                            <strong>CPF:</strong> <span id="info_cpf"></span>
                        </div>

                        <input type="hidden" id="paciente_id" name="paciente_id">
                    </div>
                </div>

                {{-- Este é o 'passo_2' que aparece após selecionar o paciente --}}
                <div id="passo_2_acolhimento" style="display: none;">
                    
                    {{-- ============================================= --}}
                    {{-- --- ADICIONADO: Card 2 (Seleção de Unidade) --- --}}
                    {{-- ============================================= --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="mb-0">2. Unidade de Atendimento</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="unidade_id" class="form-label fs-5">Selecione a Unidade:</label>
                                {{-- O Controller (método create) envia a variável $unidades --}}
                                <select name="unidade_id" id="unidade_id" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    @foreach($unidades as $unidade)
                                        <option value="{{ $unidade->idUnidadePK }}">
                                            {{ $unidade->nomeUnidade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Queixa Principal --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="mb-0">3. Queixa Principal</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="queixa_principal" class="form-label fs-5">Queixa Principal / Motivo da Visita:</label>
                                <textarea name="queixa_principal" id="queixa_principal" class="form-control" rows="4" placeholder="Descreva o que o paciente está sentindo..." required></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Botão Final --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fs-4">
                            Salvar e Encaminhar para Triagem (Enfermagem)
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
        
        // --- Lógica de busca de paciente (não muda) ---
        const campoBusca = document.getElementById('busca_paciente');
        const resultadosDiv = document.getElementById('resultados_busca_paciente');
        const pacienteIdInput = document.getElementById('paciente_id');
        const infoPacienteDiv = document.getElementById('paciente_selecionado_info');
        const passo2Div = document.getElementById('passo_2_acolhimento');

        const urlBusca = "{{ route('recepcionista.pacientes.buscar') }}";

        let timerDebounce;
        function debounce(func, delay) {
            return function(...args) {
                clearTimeout(timerDebounce);
                timerDebounce = setTimeout(() => {
                    func.apply(this, args);
                }, delay);
            };
        }

        async function buscarPacientes(termo) {
            if (termo.length < 3) { 
                resultadosDiv.innerHTML = '';
                resultadosDiv.style.display = 'none';
                return;
            }

            resultadosDiv.style.display = 'block';
            resultadosDiv.innerHTML = '<div class="list-group-item">Buscando...</div>';

            try {
                const response = await fetch(`${urlBusca}?term=${termo}`, {
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
                resultadosDiv.innerHTML = '<div class="list-group-item list-group-item-danger">Erro ao buscar. Tente novamente.</div>';
            }
        }

        function mostrarResultados(pacientes) {
            resultadosDiv.innerHTML = '';

            if (pacientes.length === 0) {
                resultadosDiv.innerHTML = '<div class="list-group-item">Nenhum paciente encontrado.</div>';
                return;
            }

            pacientes.forEach(paciente => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                
                // O PacienteController@buscar retorna 'idPaciente'
                item.innerHTML = `<strong>${paciente.nomePaciente}</strong><br>
                                  <small>CPF: ${paciente.cpfPaciente || 'Não informado'}</small>`;
                
                item.dataset.id = paciente.idPaciente; // Usa idPaciente (como na validação)
                item.dataset.nome = paciente.nomePaciente;
                item.dataset.cpf = paciente.cpfPaciente || 'Não informado';
                item.dataset.nasc = paciente.dataNascPaciente || 'Não informada';
                
                item.addEventListener('click', selecionarPaciente);
                
                resultadosDiv.appendChild(item);
            });
        }

        function selecionarPaciente(event) {
            event.preventDefault();
            const itemSelecionado = event.currentTarget;

            pacienteIdInput.value = itemSelecionado.dataset.id;

            document.getElementById('info_nome').textContent = itemSelecionado.dataset.nome;
            document.getElementById('info_data_nasc').textContent = itemSelecionado.dataset.nasc;
            document.getElementById('info_cpf').textContent = itemSelecionado.dataset.cpf;
            infoPacienteDiv.style.display = 'block';

            resultadosDiv.innerHTML = '';
            resultadosDiv.style.display = 'none';
            campoBusca.value = '';
            campoBusca.disabled = true;

            passo2Div.style.display = 'block';
        }

        campoBusca.addEventListener('keyup', debounce(function(e) {
            buscarPacientes(e.target.value);
        }, 300));
    });
</script>
@endpush