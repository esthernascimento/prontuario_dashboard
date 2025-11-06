@extends('unidade.templates.unidadeTemplate')

@section('title', 'Gerenciamento de Médicos')

@section('content')
<link rel="stylesheet" href="{{ asset('css/unidade/manutencaoMedicos.css') }}">

@php $unidade = auth()->guard('unidade')->user(); @endphp

<main class="main-dashboard">
    <div class="medico-container">
        <div class="medico-header">
            <h1><i class="bi bi-person-badge"></i> Gerenciamento de Médicos</h1>
            <a href="{{ route('unidade.medicos.create') }}" class="btn-add-medico">
                <i class="bi bi-plus-circle"></i> Cadastrar Médico
            </a>
        </div>

        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, CRM ou email..." onkeyup="filterMedicos()">
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
                        <th>Nome Médico</th>
                        <th>CRM</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicos as $medico)
                    <tr data-status="{{ optional($medico->usuario)->statusAtivoUsuario == 1 ? 'ativo' : 'inativo' }}">
                        <td>{{ $medico->nomeMedico }}</td>
                        <td>{{ $medico->crmMedico }}</td>
                        <td>{{ optional($medico->usuario)->emailUsuario ?? 'Sem email' }}</td>
                        <td>
                            @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                                <span class="status-badge status-ativo">Ativo</span>
                            @else
                                <span class="status-badge status-inativo">Inativo</span>
                            @endif
                        </td>
                        <td class="actions">
                            {{-- 🔥 CORREÇÃO: A rota agora é 'unidade.medicos.edit' --}}
                            <a href="{{ route('unidade.medicos.edit', $medico->idMedicoPK) }}" class="btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($medico->usuario)
                                <a href="#" onclick="openStatusModal('{{ $medico->idMedicoPK }}', '{{ $medico->nomeMedico }}', {{ optional($medico->usuario)->statusAtivoUsuario }})" class="btn-action" title="{{ optional($medico->usuario)->statusAtivoUsuario == 1 ? 'Desativar' : 'Ativar' }}">
                                    @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                                        <i class="bi bi-slash-circle text-danger"></i>
                                    @else
                                        <i class="bi bi-check-circle text-success"></i>
                                    @endif
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($medicos->isEmpty())
                        <tr data-status="empty-list">
                            <td colspan="5" class="no-doctors">Nenhum médico cadastrado.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            @if(method_exists($medicos, 'links'))
                {{ $medicos->links() }}
            @endif
        </div>
    </div>
</main>

{{-- MODAL DE ALTERAÇÃO DE STATUS --}}
<div id="statusMedicoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-toggle-on"></i>
            <h2>Alterar Status</h2>
        </div>
        
        <p>Tem certeza que deseja <span id="statusAction"></span> o(a) médico(a) <span id="statusMedicoNome"></span>?</p>

        {{-- 🔥 CORREÇÃO: Action com placeholder ':id:' para evitar erro de rota --}}
        <form id="statusMedicoForm" method="POST" action="{{ route('unidade.medicos.toggleStatus', ':id:') }}">
            @csrf
            <div class="modal-buttons">
                <button type="button" onclick="closeStatusModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, <span id="confirmStatusText"></span></button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE SUCESSO --}}
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
    // ... (código do modal de sucesso permanece igual) ...
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
    
    function openStatusModal(medicoId, medicoNome, currentStatus) {
        const modal = document.getElementById('statusMedicoModal');
        const nomeSpan = document.getElementById('statusMedicoNome');
        const actionSpan = document.getElementById('statusAction');
        const confirmText = document.getElementById('confirmStatusText');
        const form = document.getElementById('statusMedicoForm');
        
        const action = currentStatus == 1 ? 'desativar' : 'ativar';
        const confirmAction = currentStatus == 1 ? 'desativar' : 'ativar';

        nomeSpan.textContent = medicoNome;
        actionSpan.textContent = action;
        confirmText.textContent = confirmAction;

        // 🔥 CORREÇÃO: Substituir o placeholder ':id:' pelo ID real
        form.action = form.action.replace(':id:', medicoId);
        
        modal.style.display = 'flex';
    }

    function closeStatusModal() {
        document.getElementById('statusMedicoModal').style.display = 'none';
    }

    document.getElementById('statusMedicoModal').addEventListener('click', function(event) {
        if (event.target.id === 'statusMedicoModal') {
            closeStatusModal();
        }
    });

    // ... (restante do seu JavaScript de filtragem permanece igual) ...
    function filterMedicos() {
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
            const crm = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const status = row.dataset.status;
            const matchesSearch = name.includes(searchInput) || crm.includes(searchInput) || email.includes(searchInput);
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
                filterMedicos();
            });
        });

        document.addEventListener("click", () => {
            customSelect.classList.remove('active');
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeCustomSelect("customStatus");
        document.getElementById('searchInput').addEventListener('input', filterMedicos);
    });
</script>
@endsection