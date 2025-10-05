@extends('admin.templates.admTemplate')

@section('content')
@php
    $admin = auth()->guard('admin')->user();
    use App\Models\Paciente;
    $pacientes = $pacientes ?? Paciente::orderBy('nomePaciente')->paginate(10);
@endphp

<link rel="stylesheet" href="{{ asset('css/admin/pacientes.css') }}">

<main class="main-dashboard">
    <div class="patients-container">
        <div class="patients-header">
            <h1><i class="bi bi-people-fill"></i> Gerenciamento de Pacientes</h1>
            <a href="{{ route('admin.cadastroPaciente') }}" class="btn-add-paciente">
                <i class="bi bi-plus-circle"></i> Cadastrar Paciente
            </a>
        </div>

        <!-- Barra de pesquisa e filtros -->
        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF ou cartão SUS..." onkeyup="filterPatients()">
            </div>
            
            <div class="custom-select" id="customAge">
                <div class="selected">Todas as idades</div>
                <div class="options">
                    <div data-value="">Todas as idades</div>
                    <div data-value="crianca">Crianças (0-12)</div>
                    <div data-value="adolescente">Adolescentes (13-17)</div>
                    <div data-value="adulto">Adultos (18-59)</div>
                    <div data-value="idoso">Idosos (60+)</div>
                </div>
            </div>
            <input type="hidden" id="filterAge" value="">

            <div class="custom-select" id="customGender">
                <div class="selected">Todos os gêneros</div>
                <div class="options">
                    <div data-value="">Todos os gêneros</div>
                    <div data-value="M">Masculino</div>
                    <div data-value="F">Feminino</div>
                </div>
            </div>
            <input type="hidden" id="filterGender" value="">

            <div class="custom-select" id="customStatus">
                <div class="selected">Todos os Status</div>
                <div class="options">
                    <div data-value="">Todos os Status</div>
                    <div data-value="ativo">Ativo</div>
                    <div data-value="inativo">Inativo</div>
                </div>
            </div>
            <input type="hidden" id="filterStatus" value="">
        </div>

        <!-- Tabela de Pacientes -->
        <div class="table-wrapper">
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>NOME</th>
                        <th>CPF</th>
                        <th>IDADE</th>
                        <th>CARTÃO SUS</th>
                        <th>STATUS</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($pacientes) && count($pacientes) > 0)
                        @foreach($pacientes as $paciente)
                            @php
                                $idade = \Carbon\Carbon::parse($paciente->dataNascPaciente)->age;
                                $statusDisplay = ucfirst($paciente->statusPaciente);

                                // Lógica para o grupo de idade
                                if ($idade <= 12) {
                                    $ageGroup = 'crianca';
                                } elseif ($idade >= 13 && $idade <= 17) {
                                    $ageGroup = 'adolescente';
                                } elseif ($idade >= 18 && $idade <= 59) {
                                    $ageGroup = 'adulto';
                                } else {
                                    $ageGroup = 'idoso';
                                }

                                // Para o filtro de gênero
                                $genderCode = strtoupper(substr($paciente->generoPaciente ?? '', 0, 1));
                            @endphp
                            <tr 
                                data-age-group="{{ $ageGroup }}" 
                                data-gender="{{ $genderCode }}"
                                data-status="{{ strtolower($paciente->statusPaciente) }}"
                            >
                                <td>{{ $paciente->nomePaciente }}</td>
                                <td>{{ $paciente->cpfPaciente }}</td>
                                <td>{{ $idade }} anos</td>
                                <td>{{ $paciente->cartaoSusPaciente ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($paciente->statusPaciente) }}">
                                        {{ $statusDisplay }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn-action btn-view" title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="btn-action btn-edit" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="#" class="btn-action btn-delete" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="no-patients">Nenhum paciente encontrado.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="pagination-container">
            @if(method_exists($pacientes, 'links'))
                {{ $pacientes->links() }}
            @endif
        </div>
    </div>
</main>

{{-- MODAL DE SUCESSO UNIFICADO --}}
<div id="statusSuccessModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-check-circle-fill"></i>
            <h2>Sucesso!</h2>
        </div>
        
        <p id="successMessage"></p>

        <div class="modal-buttons">
            <button type="button" onclick="closeSuccessModal()" class="btn-excluir">Fechar</button>
        </div>
    </div>
</div>

<script>
    // ------------------------------------------
    // LÓGICA DO MODAL DE SUCESSO UNIFICADO
    // ------------------------------------------

    function openSuccessModal(message) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('statusSuccessModal').style.display = 'flex';
    }

    function closeSuccessModal() {
        document.getElementById('statusSuccessModal').style.display = 'none';
        window.location.reload(); 
    }

    document.getElementById('statusSuccessModal').addEventListener('click', function(event) {
        if (event.target.id === 'statusSuccessModal') {
            closeSuccessModal();
        }
    });

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            const message = "{{ session('success') }}"; 
            openSuccessModal(message);
        });
    @endif

    // ------------------------------------------
    // LÓGICA DE FILTRAGEM
    // ------------------------------------------

    function filterPatients() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterAge = document.getElementById('filterAge').value;
        const filterGender = document.getElementById('filterGender').value;
        const filterStatus = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const cpf = row.children[1].textContent.toLowerCase();
            const cartaoSus = row.children[3].textContent.toLowerCase();
            const ageGroup = row.dataset.ageGroup; 
            const gender = row.dataset.gender;
            const status = row.dataset.status;

            const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput) || cartaoSus.includes(searchInput);
            const matchesAge = !filterAge || ageGroup === filterAge;
            const matchesGender = !filterGender || gender === filterGender;
            const matchesStatus = !filterStatus || status === filterStatus;

            if (matchesSearch && matchesAge && matchesGender && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    function initializeCustomSelect(containerId) {
        const customSelect = document.getElementById(containerId);
        const selected = customSelect.querySelector(".selected");
        const options = customSelect.querySelector(".options");
        const hiddenInput = document.getElementById(containerId.replace('custom', 'filter'));

        selected.addEventListener("click", (e) => {
            e.stopPropagation();
            document.querySelectorAll(".custom-select .options").forEach(opt => {
                if (opt !== options) opt.parentElement.classList.remove('active');
            });
            customSelect.classList.toggle('active');
        });

        options.querySelectorAll("div").forEach(option => {
            option.addEventListener("click", () => {
                selected.textContent = option.textContent;
                hiddenInput.value = option.dataset.value;
                customSelect.classList.remove('active');
                filterPatients();
            });
        });

        document.addEventListener("click", () => {
            customSelect.classList.remove('active');
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeCustomSelect("customAge");
        initializeCustomSelect("customGender");
        initializeCustomSelect("customStatus");
        document.getElementById('searchInput').addEventListener('input', filterPatients);
    });
</script>
@endsection