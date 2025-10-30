@extends('medico.templates.medicoTemplate')

@section('title', 'Fila de Atendimento')

@section('content')

<link rel="stylesheet" href="{{ asset('css/medico/MedicoProntuario.css') }}">

<main class="main-dashboard">
    <div class="enfermeiro-container">
        
        <!-- Header -->
        <div class="enfermeiro-header">
            <h1>
                <i class="bi bi-journal-medical"></i> 
                    Pacientes Aguardando Consulta
            </h1>
        </div>

        <!-- Search Bar -->
        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" 
                       id="searchInput" 
                       placeholder="Pesquisar histórico por nome, CPF..." 
                       onkeyup="filterPatients()">
            </div>
        </div>      

            <div class="box-table">
                <table>
                    <thead>
                        <tr>
                            <th>Risco</th>
                            <th>Paciente</th>
                            <th>Hora Chegada</th>
                            <th>Queixa Principal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultas_na_fila as $consulta)
                            @if ($consulta->paciente)
                                <tr>
                                    <td>
                                        <span class="status-badge status-{{ $consulta->classificacao_risco }}">
                                            {{ ucfirst($consulta->classificacao_risco) }}
                                        </span>
                                    </td>
                                    <td>{{ $consulta->paciente->nomePaciente }}</td>
                                    <td>
                                        {{ $consulta->dataConsulta ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('H:i') : 'N/A' }}
                                    </td>
                                    <td class="queixa-principal-text" title="{{ $consulta->queixa_principal }}">
                                        {{ $consulta->queixa_principal }}
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('medico.prontuario.edit', $consulta->idConsultaPK) }}"
                                           class="btn-action btn-atender">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            
                                        </a>
                                        <a href="{{ route('medico.visualizarProntuario', $consulta->paciente->idPaciente) }}"
                                           class="btn-action btn-view"
                                           title="Ver Histórico">
                                           <i class="bi bi-clipboard2-pulse"></i>


                                        </a>
                                    </td>
                                </tr>
                            @else
                                <tr class="consulta-invalida">
                                    <td>
                                        @if($consulta->classificacao_risco)
                                            <span class="status-badge status-{{ $consulta->classificacao_risco }}">
                                                {{ ucfirst($consulta->classificacao_risco) }}
                                            </span>
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </td>
                                    <td colspan="3">
                                        Consulta (ID: {{ $consulta->idConsultaPK }}) com paciente inválido ou não encontrado.
                                    </td>
                                    <td class="actions">-</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="no-enfermeiros">
                                    Nenhum paciente aguardando consulta no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<script>
    function filterPatients() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.fila-container tbody tr');

        rows.forEach(row => {
            // Ignora a linha de "nenhum paciente encontrado"
            if (row.querySelector('.no-enfermeiros')) {
                row.style.display = searchInput === '' ? '' : 'none';
                return;
            }

            // Pega o texto de todas as células (exceto ações)
            const cells = row.querySelectorAll('td:not(.actions)');
            let textContent = '';
            cells.forEach(cell => {
                textContent += cell.textContent.toLowerCase() + ' ';
            });

            // Mostra ou esconde baseado na busca
            row.style.display = textContent.includes(searchInput) ? '' : 'none';
        });
    }
</script>

@endsection