@extends('unidade.templates.unidadeTemplate')

@section('title', 'Manutenção de Enfermeiros')

@section('content')
<link rel="stylesheet" href="{{ asset('css/unidade/manutencaoRecepcionista.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
    <div class="enfermeiro-container">
        <div class="enfermeiro-header">
            <h1><i class="bi bi-person-vcard"></i> Gerenciamento de Recepcionista</h1>
            <a href="{{ route('admin.enfermeiro.create') }}" class="btn-add-enfermeiro">
                <i class="bi bi-plus-circle"></i> Cadastrar Recepcionista
            </a>
        </div>


        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, COREN ou email..." onkeyup="filterEnfermeiros()">
            </div>
            
            <div class="custom-select" id="customStatus">
                <div class="selected">Status</div>
                <div class="options">
                    <div data-value="">Todos</div>
                    <div data-value="ativo">Ativo</div>
                    <div data-value="inativo">Inativo</div>
                </div>
            </div>
            <input type="hidden" id="filterStatus" value="">
        </div>

        <div class="box-table">
            <table>
                <thead>
                    <tr>
                        <th>Nome Recepcionista</th>
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
                        <td>{{ optional($enfermeiro->usuario)->emailUsuario ?? 'Sem email' }}</td>
                        <td>
                            @if(optional($enfermeiro->usuario)->statusAtivoUsuario == 1)
                                <span class="status-badge status-ativo">Ativo</span>
                            @else
                                <span class="status-badge status-inativo">Inativo</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.enfermeiro.editar', $enfermeiro->idEnfermeiroPK) }}" class="btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($enfermeiro->usuario)
                                <a href="#" onclick="openStatusModal('{{ $enfermeiro->idEnfermeiroPK }}', '{{ $enfermeiro->nomeEnfermeiro }}', {{ optional($enfermeiro->usuario)->statusAtivoUsuario }})" class="btn-action" title="{{ optional($enfermeiro->usuario)->statusAtivoUsuario == 1 ? 'Desativar' : 'Ativar' }}">
                                    @if(optional($enfermeiro->usuario)->statusAtivoUsuario == 1)
                                        <i class="bi bi-slash-circle text-danger"></i>
                                    @else
                                        <i class="bi bi-check-circle text-success"></i>
                                    @endif
                                </a>
                            @endif
                            {{-- REMOVEMOS O BOTÃO DE EXCLUSÃO DEFINITIVAMENTE --}}
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($enfermeiros->isEmpty())
                        <tr data-status="empty-list">
                            <td colspan="5" class="no-enfermeiros">Nenhum recepcionista cadastrado.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            @if(method_exists($enfermeiros, 'links'))
                {{ $enfermeiros->links() }}
            @endif
        </div>
    </div>
</main>

{{-- MODAL DE EXCLUSÃO FOI REMOVIDO --}}

{{-- MODAL DE ALTERAÇÃO DE STATUS (EXISTENTE) --}}
<div id="statusEnfermeiroModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-toggle-on"></i>
            <h2>Alterar Status</h2>
        </div>
        
        <p>Tem certeza que deseja <span id="statusAction"></span> o(a) recepcionista<span id="statusEnfermeiroNome"></span>?</p>

        <form id="statusEnfermeiroForm" method="POST">
            @csrf
            <div class="modal-buttons">
                <button type="button" onclick="closeStatusModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, <span id="confirmStatusText"></span></button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE SUCESSO UNIFICADO (EXISTENTE) --}}
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

   
    function openStatusModal(enfermeiroId, enfermeiroNome, currentStatus) {
        const modal = document.getElementById('statusEnfermeiroModal');
        const nomeSpan = document.getElementById('statusEnfermeiroNome');
        const actionSpan = document.getElementById('statusAction');
        const confirmText = document.getElementById('confirmStatusText');
        const form = document.getElementById('statusEnfermeiroForm');
        
        const action = currentStatus == 1 ? 'desativar' : 'ativar';
        const confirmAction = currentStatus == 1 ? 'desativar' : 'ativar';

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

    // ------------------------------------------
    // LÓGICA DE FILTRAGEM
    // ------------------------------------------

    function filterEnfermeiros() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;

        const rows = document.querySelectorAll('tbody tr');
        let visibleRowsCount = 0;
        let emptyRow = null;

        rows.forEach(row => {
            if (row.dataset.status === 'empty-list') {
                emptyRow = row;
                row.style.display = 'none';
                return;
            }

            const name = row.children[0].textContent.toLowerCase();
            const coren = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const status = row.dataset.status;

            const matchesSearch = name.includes(searchInput) || coren.includes(searchInput) || email.includes(searchInput);
            const matchesStatus = !filterStatus || status === filterStatus;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleRowsCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        if (emptyRow) {
            if (visibleRowsCount === 0) {
                emptyRow.style.display = ''; 
            } else {
                emptyRow.style.display = 'none';
            }
        }
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
                filterEnfermeiros();
            });
        });

        document.addEventListener("click", () => {
            customSelect.classList.remove('active');
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeCustomSelect("customStatus");
        document.getElementById('searchInput').addEventListener('input', filterEnfermeiros);
    });
</script>
@endsection