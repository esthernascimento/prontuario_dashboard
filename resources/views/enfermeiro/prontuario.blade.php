@extends('enfermeiro.templates.enfermeiroTemplate')

@section('title', 'Prontuário dos Pacientes')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/prontuario.css') }}">

<main class="main-dashboard">
    <div class="enfermeiro-container">
        <div class="enfermeiro-header">
            <h1><i class="bi bi-journal-medical"></i> Prontuário dos Pacientes</h1>
        </div>

        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF..." onkeyup="filterPatients()">
            </div>

            <div class="filters">
                <div class="custom-select" id="customStatus">
                    <div class="selected">Status</div>
                    <div class="options">
                        <div data-value="">Todos</div>
                        <div data-value="internado">Internado</div>
                        <div data-value="alta">Alta</div>
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
                        <th>Status</th>
                        <th>Prontuário</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pacientes as $paciente)
                    <tr data-status="{{ $paciente->status }}">
                        <td>{{ $paciente->nome }}</td>
                        <td>{{ $paciente->cpf }}</td>
                        <td>{{ \Carbon\Carbon::parse($paciente->data_nascimento)->format('d/m/Y') }}</td>
                        <td>
                            @if($paciente->status === 'internado')
                                <span style="color: #0a400c;">Internado</span>
                            @else
                                <span style="color: red;">Alta</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('enfermeiro.paciente.prontuario', $paciente->id) }}" title="Visualizar Prontuário">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    function filterPatients() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;

        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const cpf = row.children[1].textContent.toLowerCase();
            const status = row.dataset.status;

            const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput);
            const matchesStatus = !filterStatus || status === filterStatus;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    const customSelect = document.getElementById("customStatus");
    const selected = customSelect.querySelector(".selected");
    const options = customSelect.querySelector(".options");
    const hiddenInput = document.getElementById("filterStatus");

    selected.addEventListener("click", () => {
        options.style.display = options.style.display === "flex" ? "none" : "flex";
    });

    options.querySelectorAll("div").forEach(option => {
        option.addEventListener("click", () => {
            selected.textContent = option.textContent;
            hiddenInput.value = option.dataset.value;
            options.style.display = "none";
            filterPatients();
        });
    });

    document.addEventListener("click", e => {
        if (!customSelect.contains(e.target)) {
            options.style.display = "none";
        }
    });
</script>

@endsection
