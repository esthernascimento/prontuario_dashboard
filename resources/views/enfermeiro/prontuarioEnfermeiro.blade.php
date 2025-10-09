@extends('enfermeiro.templates.enfermeiroTemplate')

@section('title', 'Prontuário dos Pacientes')

@section('content')
<link rel="stylesheet" href="{{ asset('css/enfermeiro/prontuario.css') }}">

<main class="main-dashboard">
    <div class="enfermeiro-container">
        <div class="enfermeiro-header">
            <h1><i class="bi bi-journal-medical"></i> Prontuário dos Pacientes</h1>
        </div>

        {{-- Filtros e Busca --}}
        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF..." onkeyup="filterPatients()">
            </div>

            <div class="filters">
                <div class="custom-select" id="customStatus">
                    <input type="text" readonly class="selected-filter-input selected" value="Status" id="selectedFilterText">
                    <i class="bi bi-chevron-down select-icon"></i>

                    <div class="options">
                        <div data-value="">Todos</div>
                        <div data-value="1">Ativo</div>
                        <div data-value="2">Alta</div>
                    </div>
                </div>
                <input type="hidden" id="filterStatus" value="">
            </div>
        </div>

        <div class="box-table">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Nascimento</th>
                        <th class="status-header">Status</th>
                        <th class="actions-header">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pacientes as $paciente)
                        @php
                            $statusValue = $paciente->statusPaciente ?? 1;
                            $statusText = $statusValue == 1 ? 'Ativo' : 'Alta';
                            $statusClass = $statusValue == 1 ? 'status-ativo' : 'status-alta';
                        @endphp
                        <tr data-status="{{ $statusValue }}" data-name="{{ strtolower($paciente->nomePaciente) }}" data-cpf="{{ $paciente->cpfPaciente }}">
                            <td>{{ $paciente->nomePaciente }}</td>
                            <td>{{ $paciente->cpfPaciente }}</td>
                            <td>{{ $paciente->dataNascPaciente ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') : 'N/A' }}</td>
                            <td class="status-cell">
                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td class="actions">
                                {{-- Visualizar Prontuário --}}
                                <a href="{{ route('enfermeiro.visualizarProntuario', $paciente->idPaciente) }}"
                                   class="btn-action btn-view"
                                   title="Visualizar Prontuário e Histórico">
                                   <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- Criar Nova Anotação --}}
                                <a href="{{ route('enfermeiro.anotacao.create', $paciente->idPaciente) }}"
                                   class="btn-action btn-add-anotacao"
                                   title="Criar Nova Anotação de Enfermagem">
                                   <i class="bi bi-file-earmark-text-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-enfermeiros">Nenhum paciente encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

         
        </div>
    </div>
</main>

<script>
function filterPatients() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const filterStatusValue = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        if (!row.dataset.name) { row.style.display = 'none'; return; }

        const name = row.dataset.name;
        const cpf = row.dataset.cpf;
        const status = row.dataset.status;

        const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput);
        const matchesStatus = filterStatusValue === '' || status === filterStatusValue;

        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });

    checkNoResults();
}

const customSelect = document.getElementById("customStatus");
const selectedInput = customSelect.querySelector(".selected-filter-input");
const options = customSelect.querySelector(".options");
const hiddenStatusInput = document.getElementById('filterStatus');

selectedInput.addEventListener("click", () => customSelect.classList.toggle('active'));

options.querySelectorAll("div").forEach(option => {
    option.addEventListener("click", () => {
        selectedInput.value = option.textContent;
        hiddenStatusInput.value = option.getAttribute('data-value');
        customSelect.classList.remove('active');
        filterPatients();
    });
});

document.addEventListener("click", e => {
    if (!customSelect.contains(e.target)) customSelect.classList.remove('active');
});

function checkNoResults() {
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    rows.forEach(row => { if (row.dataset.name && row.style.display !== 'none') visibleCount++; });

    const noPatientsRow = document.querySelector('td.no-enfermeiros')?.closest('tr');
    if (noPatientsRow) { noPatientsRow.style.display = visibleCount === 0 ? '' : 'none'; }
}

window.onload = checkNoResults;
</script>
@endsection
