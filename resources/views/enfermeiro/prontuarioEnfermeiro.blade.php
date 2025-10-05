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
                            
                            {{-- 1. Nome --}}
                            <td>{{ $paciente->nome }}</td>
                            
                            {{-- 2. CPF - CORRIGIDO: Esta célula estava faltando, desalinhando o restante. --}}
                            <td>{{ $paciente->cpf }}</td> 
                            
                            {{-- 3. Nascimento - Agora na posição correta --}}
                            <td>{{ Carbon\Carbon::parse($paciente->data_nascimento)->format('d/M/Y') }}</td>
                            
                            {{-- 4. Status - Agora na posição correta --}}
                            <td>
                                @if ($paciente->status === 'Internado')
                                    <span style="color: #00A40C;">Internado</span>
                                @else
                                    <span style="color: #0a400c;">Alta</span>
                                @endif
                            </td>
                            
                            {{-- 5. Prontuário (Actions) - CORRIGIDO: Adicionado o tratamento de erro para o link. --}}
                            <td class="actions">
                                @if ($paciente->id)
                                    <a href="{{ route('enfermeiro.paciente.prontuario', ['id' => $paciente->id]) }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                @else
                                    {{-- Exibe um traço se o ID estiver faltando, prevenindo o erro. --}}
                                    <span>-</span> 
                                @endif
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

        // O índice do CPF mudou de 1 para 2 após a correção no HTML
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const cpf = row.children[1].textContent.toLowerCase(); // O CPF agora é o segundo elemento (índice 1)
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