@extends('recepcionista.templates.recTemplate')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 offset-md-1">

                @if(session('success'))
                    <div class="alert alert-success shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" role="alert">
                        <strong>Ops!</strong> Havia algo errado com os dados:
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="mb-4">Iniciar Novo Atendimento (Acolhimento)</h2>

                <div class="welcome-banner-rec mb-5">
                    <div class="banner-left-rec">
                        <img src="{{ asset('img/recepcionista-logo1.png') }}" alt="Ícone de Recepcionista"
                            class="banner-logo-rec">
                    </div>

                    <div class="banner-center-rec">
                        <h2>
                            Bem-vindo(a),
                            <span class="name-rec">
                                Recepcionista {{ $recepcionista->nomeRecepcionista ?? 'Usuário' }}
                            </span>
                        </h2>
                        <p>Sua dedicação é o primeiro passo para a jornada de cuidado dos pacientes.</p>
                    </div>
                </div>

                <form action="{{ route('recepcionista.acolhimento.store') }}" method="POST">
                    @csrf

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="mb-0">1. Identificação do Paciente</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3" id="busca-container">
                                <label for="busca_paciente" class="form-label fs-5">Buscar Paciente (Nome, CPF ou Cartão
                                    SUS):</label>

                                <input type="text" id="busca_paciente" class="form-control form-control-lg"
                                    placeholder="Digite 3 ou mais letras para buscar..." autocomplete="off">

                                <div id="resultados_busca_paciente" class="list-group mt-2"></div>
                            </div>

                            <div id="paciente_selecionado_info" class="alert alert-info" style="display: none;">
                                <strong>Paciente:</strong> <span id="info_nome"></span><br>
                                <strong>Data Nasc:</strong> <span id="info_data_nasc"></span><br>
                                <strong>CPF:</strong> <span id="info_cpf"></span>
                            </div>

                            <input type="hidden" id="paciente_id" name="paciente_id" required>
                        </div>
                    </div>

                    <div id="passo_2_acolhimento" style="display: none;">

                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h4 class="mb-0">2. Queixa Principal e Classificação de Risco</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <label for="queixa_principal" class="form-label fs-5">Queixa Principal / Motivo da
                                        Visita:</label>
                                    <textarea name="queixa_principal" id="queixa_principal" class="form-control" rows="4"
                                        placeholder="Descreva o que o paciente está sentindo..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label fs-5 mb-3">Classificação de Risco (Protocolo de
                                        Manchester):</label>
                                    <div id="botoes_risco" class="d-flex justify-content-between flex-wrap">

                                        <input type="radio" name="classificacao_risco" id="risco_vermelho" value="vermelho"
                                            class="btn-check" required>
                                        <label class="btn btn-danger btn-lg flex-fill" for="risco_vermelho">
                                            <i class="bi bi-heart-fill"></i> VERMELHO <br> (Emergência)
                                        </label>

                                        <input type="radio" name="classificacao_risco" id="risco_laranja" value="laranja"
                                            class="btn-check">
                                        <label class="btn btn-warning btn-lg flex-fill" for="risco_laranja">
                                            <i class="bi bi-exclamation-triangle-fill"></i> LARANJA <br> (Muito Urgente)
                                        </label>

                                        <input type="radio" name="classificacao_risco" id="risco_amarelo" value="amarelo"
                                            class="btn-check">
                                        <label class="btn btn-lg flex-fill" for="risco_amarelo">
                                            <i class="bi bi-hourglass-split"></i> AMARELO <br> (Urgente)
                                        </label>

                                        <input type="radio" name="classificacao_risco" id="risco_verde" value="verde"
                                            class="btn-check">
                                        <label class="btn btn-success btn-lg flex-fill" for="risco_verde">
                                            <i class="bi bi-thermometer-half"></i> VERDE <br> (Pouco Urgente)
                                        </label>

                                        <input type="radio" name="classificacao_risco" id="risco_azul" value="azul"
                                            class="btn-check">
                                        <label class="btn btn-primary btn-lg flex-fill" for="risco_azul">
                                            <i class="bi bi-clock-fill"></i> AZUL <br> (Não Urgente)
                                        </label>
                                    </div>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function () {

            const campoBusca = document.getElementById('busca_paciente');
            const resultadosDiv = document.getElementById('resultados_busca_paciente');
            const pacienteIdInput = document.getElementById('paciente_id');
            const infoPacienteDiv = document.getElementById('paciente_selecionado_info');
            const passo2Div = document.getElementById('passo_2_acolhimento');

            const urlBusca = "{{ route('recepcionista.pacientes.buscar') }}";

            let timerDebounce;
            function debounce(func, delay) {
                return function (...args) {
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

                    // Ajuste os nomes das colunas se necessário (ex: paciente.nome)
                    item.innerHTML = `<strong>${paciente.nomePaciente}</strong><br>
                                      <small>CPF: ${paciente.cpfPaciente || 'Não informado'}</small>`;

                    // Ajuste os nomes das colunas se necessário (ex: paciente.id)
                    item.dataset.id = paciente.idPaciente;
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

            campoBusca.addEventListener('keyup', debounce(function (e) {
                buscarPacientes(e.target.value);
            }, 300));
        });
    </script>
@endpush