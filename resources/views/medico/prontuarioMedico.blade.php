@extends('medico.templates.medicoTemplate')

@section('title', 'Fila de Atendimento')

@section('content')

<link rel="stylesheet" href="{{ asset('css/medico/MedicoProntuario.css') }}"> 

<main class="main-dashboard">

  
    <div class="fila-container">
        <div class="fila-header">
            <h2><i class="bi bi-person-lines-fill"></i> Pacientes Aguardando Consulta</h2>
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
                                    {{-- Badge colorida baseada na classificação --}}
                                    <span class="status-badge status-{{ $consulta->classificacao_risco }}">
                                        {{ ucfirst($consulta->classificacao_risco) }}
                                    </span>
                                </td>
                                <td>{{ $consulta->paciente->nomePaciente }}</td>
                                <td>{{ $consulta->dataConsulta ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('H:i') : 'N/A' }}</td>
                                <td class="queixa-principal-text" title="{{ $consulta->queixa_principal }}">
                                    {{ $consulta->queixa_principal }}
                                </td>
                                <td class="actions">
                                    {{-- Botão "Atender" leva para a rota de edição da consulta --}}
                                    <a href="{{ route('medico.prontuario.edit', $consulta->idConsultaPK) }}" 
                                       class="btn-action btn-atender" 
                                       title="Atender Paciente">
                                        <i class="bi bi-play-circle-fill"></i> Atender
                                    </a>
                                    {{-- Link para visualizar o histórico completo do paciente --}}
                                    <a href="{{ route('medico.visualizarProntuario', $consulta->paciente->idPaciente) }}" 
                                        class="btn-action btn-view" 
                                        title="Ver Histórico Completo">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @else
                            {{-- Linha especial para consultas sem paciente válido --}}
                            <tr class="consulta-invalida">
                                <td>
                                     {{-- Mostra o risco se existir, mesmo sem paciente --}}
                                    @if($consulta->classificacao_risco)
                                        <span class="status-badge status-{{ $consulta->classificacao_risco }}">
                                            {{ ucfirst($consulta->classificacao_risco) }}
                                        </span>
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </td>
                                {{-- Mensagem indicando o problema --}}
                                <td colspan="3">Consulta (ID: {{ $consulta->idConsultaPK }}) com paciente inválido ou não encontrado.</td>
                                <td class="actions">-</td> {{-- Sem ações para consulta inválida --}}
                            </tr>
                        @endif
                    @empty
                    <tr>
                        <td colspan="5" class="no-enfermeiros">Nenhum paciente aguardando consulta no momento.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="enfermeiro-container"> 
        <div class="enfermeiro-header" style="background: none; padding: 0 10px; margin-bottom: 15px;"> 
             <h1 style="color: var(--text-secondary); font-size: 1.5rem;"><i class="bi bi-journal-medical" style="color: var(--text-muted);"></i> Histórico Geral de Pacientes</h1>
        </div>

        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar histórico por nome, CPF..." onkeyup="filterPatients()">
            </div>
            {{-- Filtro de status pode ser útil aqui --}}
            {{-- Seu código do <div class="custom-select" id="customStatus"> aqui... --}}
        </div>
        
        <div class="box-table"> {{-- Tabela de histórico usa o mesmo estilo --}}
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Nascimento</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop na variável $pacientes (histórico) enviada pelo Controller --}}
                    @forelse ($pacientes as $paciente)
                    <tr data-status="{{ $paciente->statusPaciente ? '1' : '0' }}"
                        data-name="{{ strtolower($paciente->nomePaciente) }}" 
                        data-cpf="{{ $paciente->cpfPaciente }}">
                        <td>{{ $paciente->nomePaciente }}</td>
                        <td>{{ $paciente->cpfPaciente }}</td>
                        <td>{{ $paciente->dataNascPaciente ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            @if ($paciente->statusPaciente)
                                <span class="status-badge status-ativo">Ativo</span>
                            @else
                                <span class="status-badge status-inativo">Inativo</span>
                            @endif
                        </td>
                        <td class="actions">
                            {{-- Visualizar Histórico --}}
                            <a href="{{ route('medico.visualizarProntuario', $paciente->idPaciente) }}" 
                               class="btn-action btn-view" 
                               title="Visualizar Prontuário Completo">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            {{-- Criar Consulta (se o médico iniciar do zero) --}}
                            @if ($paciente->statusPaciente) {{-- Só permite criar para ativos --}}
                            <a href="{{ route('medico.cadastrarProntuario', $paciente->idPaciente) }}" 
                               class="btn-action btn-add-consulta" 
                               title="Criar Nova Consulta (Fora da Fila)">
                                <i class="bi bi-plus-circle-fill"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="no-enfermeiros">Nenhum paciente cadastrado no sistema.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    // Função de filtro para a TABELA DE HISTÓRICO
    function filterPatients() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        // Seleciona apenas as linhas da SEGUNDA tabela (histórico)
        // Ajustado para selecionar a tabela correta dentro do segundo .enfermeiro-container
        const rows = document.querySelectorAll('.enfermeiro-container:not(.fila-container) tbody tr'); 

        rows.forEach(row => {
            // Ignora a linha de "nenhum encontrado"
            if (row.querySelector('.no-enfermeiros')) { 
                // Se a busca estiver vazia, mostra a mensagem, senão esconde
                row.style.display = searchInput === '' ? '' : 'none'; 
                return;
            }
             if (!row.dataset.name) return; // Segurança extra

            const name = row.dataset.name;
            const cpf = row.dataset.cpf;
            const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput);

            row.style.display = matchesSearch ? '' : 'none';
        });
    }

 
</script>
@endsection

