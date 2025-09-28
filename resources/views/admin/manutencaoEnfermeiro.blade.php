@extends('admin.templates.admTemplate')

@section('title', 'Manutenção de Enfermeiros')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/manutencaoEnfermeiros.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp


    {{-- Esta barra de sucesso será exibida APENAS se não houver um modal configurado --}}
    @if(session('success') && !session('status_changed') && !session('updated') && !session('deleted'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <main class="main-dashboard">
        <div class="enfermeiro-container">
            <div class="enfermeiro-header">
                <h1><i class="bi bi-person-vcard-fill"></i> Gerenciamento de Enfermeiro</h1>
                <a href="{{ route('admin.enfermeiro.create') }}" class="btn-add-enfermeiro">
                <i class="bi bi-plus-circle"></i> Cadastrar Enfermeiro
                </a>
            </div>


            <div class="search-filters">
                <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, COREN ou email..." onkeyup="filterPatients()">
                </div>
                <div class="filters">
                <div class="custom-select" id="customStatus">
                    <div class="selected">Status</div>
                    <div class="options">
                    <div data-value="">Status</div>
                    <div data-value="ativo">Ativo</div>
                    <div data-value="inativo">Inativo</div>
                    </div>
                </div>
                <input type="hidden" id="filterStatus" value="">
                </div>
            </div>

            <div class="box-table">
                <table>
                <thead>
                    <tr>
                    <th>Nome Enfermeiro</th>
                    <th>COREN</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enfermeiros as $enfermeiro)
                    <tr data-status="{{ optional($enfermeiro->usuario)->statusAtivoUsuario == 1 ? 'ativo' : 'inativo' }}">
                        <td>{{ $enfermeiro->nomeEnfermeiro }}</td>
                        <td>{{ $enfermeiro->corenEnfermeiro }}</td>
                        <td>{{ $enfermeiro->usuario->emailUsuario ?? 'Sem email' }}</td>
                        <td>
                        @if(optional($enfermeiro->usuario)->statusAtivoUsuario == 1)
                            <span style="color: green;">Ativo</span>
                        @else
                            <span style="color: red;">Inativo</span>
                        @endif
                        </td>
                        <td class="actions">
                        <a href="{{ route('admin.enfermeiro.editar', $enfermeiro->idEnfermeiroPK) }}">
                            <i class="bi bi-pencil" title="Editar"></i>
                        </a>

                        @if($enfermeiro->usuario)
                            <a href="#" onclick="openStatusModal('{{ $enfermeiro->idEnfermeiroPK }}', '{{ $enfermeiro->nomeEnfermeiro }}', {{ $enfermeiro->usuario->statusAtivoUsuario }})">
                                @if($enfermeiro->usuario->statusAtivoUsuario == 1)
                                <i class="bi bi-slash-circle text-danger" title="Desativar"></i>
                                @else
                                <i class="bi bi-check-circle text-success" title="Ativar"></i>
                                @endif
                            </a>
                        @endif

                        {{-- CHAMADA JS PARA ABRIR O MODAL DE EXCLUSÃO --}}
                        <a href="#" onclick="openDeleteEnfermeiroModal('{{ $enfermeiro->idEnfermeiroPK }}', '{{ $enfermeiro->nomeEnfermeiro }}')">
                            <i class="bi bi-trash" title="Excluir"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </main>

{{-- MODAL DE EXCLUSÃO --}}
<div id="deleteEnfermeiroModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-trash-fill"></i>
            <h2>Excluir Enfermeiro(a)</h2>
        </div>
        
        <p>Tem certeza que deseja excluir o(a) enfermeiro(a) <span id="enfermeiroNome"></span>?</p>

        <form id="deleteEnfermeiroForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal-buttons">
                <button type="button" onclick="closeDeleteEnfermeiroModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, excluir</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE ALTERAÇÃO DE STATUS --}}
<div id="statusEnfermeiroModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-toggle-on"></i>
            <h2>Alterar Status</h2>
        </div>
        
        <p>Tem certeza que deseja <span id="statusAction"></span> o(a) enfermeiro(a) <span id="statusEnfermeiroNome"></span>?</p>

        <form id="statusEnfermeiroForm" method="POST">
            @csrf
            <div class="modal-buttons">
                <button type="button" onclick="closeStatusModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, <span id="confirmStatusText"></span></button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE SUCESSO UNIFICADO --}}
<div id="statusSuccessModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header" style="color: #198754;"> {{-- Cor verde para sucesso --}}
            <i class="bi bi-check-circle-fill"></i>
            <h2>Sucesso!</h2>
        </div>
        
        <p id="successMessage"></p>

        <div class="modal-buttons">
            {{-- Botão de fechar (Ok) com a cor verde de sucesso --}}
            <button type="button" onclick="closeSuccessModal()" class="btn-excluir" style="background-color: #198754;">Fechar</button>
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
    }

    // Fecha o modal de sucesso clicando fora
    document.getElementById('statusSuccessModal').addEventListener('click', function(event) {
        if (event.target.id === 'statusSuccessModal') {
            closeSuccessModal();
        }
    });


    // ------------------------------------------
    // Verificação de Sucesso ao Carregar a Página
    // Agora verifica 'status_changed', 'updated' e 'deleted'
    // ------------------------------------------

    @if(session('status_changed') || session('updated') || session('deleted'))
        document.addEventListener('DOMContentLoaded', () => {
            // A mensagem de sucesso é a mesma para todas as ações
            const message = "{{ session('success') }}"; 
            openSuccessModal(message);
        });
    @endif


    // ------------------------------------------
    // LÓGICA DOS MODAIS EXISTENTES
    // ------------------------------------------

    // Funções do Modal de Exclusão
    function openDeleteEnfermeiroModal(enfermeiroId, enfermeiroNome) {
        const modal = document.getElementById('deleteEnfermeiroModal');
        const nomeSpan = document.getElementById('enfermeiroNome');
        const form = document.getElementById('deleteEnfermeiroForm');

        nomeSpan.textContent = enfermeiroNome;

        const deleteRoute = "{{ route('admin.enfermeiro.excluir', ['id' => 'PLACEHOLDER_ID']) }}";
        form.action = deleteRoute.replace('PLACEHOLDER_ID', enfermeiroId);
        
        modal.style.display = 'flex';
    }

    function closeDeleteEnfermeiroModal() {
        document.getElementById('deleteEnfermeiroModal').style.display = 'none';
    }

    document.getElementById('deleteEnfermeiroModal').addEventListener('click', function(event) {
        if (event.target.id === 'deleteEnfermeiroModal') {
            closeDeleteEnfermeiroModal();
        }
    });

    // Funções do Modal de Status
    function openStatusModal(enfermeiroId, enfermeiroNome, currentStatus) {
        const modal = document.getElementById('statusEnfermeiroModal');
        const nomeSpan = document.getElementById('statusEnfermeiroNome');
        const actionSpan = document.getElementById('statusAction');
        const confirmText = document.getElementById('confirmStatusText');
        const form = document.getElementById('statusEnfermeiroForm');
        
        const action = currentStatus ? 'desativar' : 'ativar';
        const confirmAction = currentStatus ? 'desativar' : 'ativar';

        nomeSpan.textContent = enfermeiroNome;
        actionSpan.textContent = action;
        confirmText.textContent = confirmAction;

        const statusRoute = "{{ route('admin.enfermeiro.toggleStatus', ['id' => 'PLACEHOLDER_ID']) }}";
        form.action = statusRoute.replace('PLACEHOLDER_ID', enfermeiroId);
        
        modal.style.display = 'flex';
    }

    function closeStatusModal() {
        document.getElementById('statusEnfermeiroModal').style.display = 'none';
    }

    document.getElementById('statusEnfermeiroModal').addEventListener('click', function(event) {
        if (event.target.id === 'statusEnfermeiroModal') {
            closeStatusModal();
        }
    });

</script>


<script>

    function filterPatients() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;

        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            const crm = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const status = row.dataset.status;

            const matchesSearch = name.includes(searchInput) || crm.includes(searchInput) || email.includes(searchInput);
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