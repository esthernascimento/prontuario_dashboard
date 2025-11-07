@extends('unidade.templates.unidadeTemplate')

@section('title', 'Gerenciamento de Médicos')

@section('content')
<link rel="stylesheet" href="{{ asset('css/unidade/manutencaoMedicos.css') }}">

@php $unidade = auth()->guard('unidade')->user(); @endphp

<main class="main-dashboard">
    <div class="medico-container">
        <!-- DASHBOARD DE MÉTRICAS -->
        <div class="metrics-dashboard">
            <div class="metric-card total">
                <div class="metric-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Total de Médicos</span>
                    <span class="metric-value" id="metric-total">{{ $totalMedicos ?? 0 }}</span>
                </div>
            </div>

            <div class="metric-card active">
                <div class="metric-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Ativos</span>
                    <span class="metric-value">{{ $medicos->where('usuario.statusAtivoUsuario', 1)->count() }}</span>
                </div>
            </div>

            <div class="metric-card inactive">
                <div class="metric-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">Inativos</span>
                    <span class="metric-value">{{ $medicos->where('usuario.statusAtivoUsuario', 0)->count() }}</span>
                </div>
            </div>

            <div class="metric-card new">
                <div class="metric-icon">
                    <i class="bi bi-star-fill"></i>
            </div>
                <div class="metric-info">
                    <span class="metric-label">Novos este Mês</span>
                    <span class="metric-value" id="metric-novos">{{ $novosCount ?? 0 }}</span> 
                </div>
            </div>
        </div>

        <!-- HEADER COM AÇÕES -->
        <div class="medico-header">
            <div class="header-title">
                <h1><i class="bi bi-person-badge"></i> Gerenciamento de Médicos</h1>
                <span class="subtitle">Gerencie todos os médicos da unidade</span>
            </div>
            
            <div class="header-actions">
                <button onclick="exportToExcel()" class="btn-export" title="Exportar para Excel">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Exportar
                </button>
                <a href="{{ route('unidade.medicos.create') }}" class="btn-add-medico">
                    <i class="bi bi-plus-circle"></i> Cadastrar Médico
                </a>
            </div>
        </div>

        <!-- FILTROS AVANÇADOS -->
        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, CRM ou email..." onkeyup="filterMedicos()">
            </div>
            
            <div class="custom-select" id="customStatus">
                <div class="selected">
                    <i class="bi bi-filter"></i> Status
                </div>
                <div class="options">
                    <div data-value="">Todos</div>
                    <div data-value="ativo">Ativo</div>
                    <div data-value="inativo">Inativo</div>
                </div>
            </div>
            <input type="hidden" id="filterStatus" value="">

            <div class="view-toggle">
                <button class="view-btn active" data-view="list" onclick="changeView('list')" title="Visualização em Lista">
                    <i class="bi bi-list-ul"></i>
                </button>
                <button class="view-btn" data-view="grid" onclick="changeView('grid')" title="Visualização em Cards">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
            </div>

            <button class="btn-refresh" onclick="location.reload()" title="Atualizar">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>

        <!-- AÇÕES EM MASSA -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <div class="bulk-info">
                <i class="bi bi-check-square"></i>
                <span id="selectedCount">0</span> médico(s) selecionado(s)
            </div>
            <div class="bulk-buttons">
                <button onclick="bulkActivate()" class="btn-bulk-activate">
                    <i class="bi bi-check-circle"></i> Ativar Selecionados
                </button>
                <button onclick="bulkDeactivate()" class="btn-bulk-deactivate">
                    <i class="bi bi-x-circle"></i> Desativar Selecionados
                </button>
                <button onclick="clearSelection()" class="btn-bulk-clear">
                    <i class="bi bi-x"></i> Limpar Seleção
                </button>
            </div>
        </div>

        <!-- VISUALIZAÇÃO EM LISTA -->
        <div class="box-table" id="listView">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th onclick="sortTable('nome')" style="cursor: pointer;">
                            Nome Médico <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th onclick="sortTable('crm')" style="cursor: pointer;">
                            CRM <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicos as $medico)
                    <tr data-status="{{ optional($medico->usuario)->statusAtivoUsuario == 1 ? 'ativo' : 'inativo' }}" 
                        data-id="{{ $medico->idMedicoPK }}"
                        class="{{ $medico->created_at >= now()->subDays(7) ? 'new-entry' : '' }}">
                        <td>
                            <input type="checkbox" class="select-medico" value="{{ $medico->idMedicoPK }}" onchange="updateBulkActions()">
                        </td>
                        <td>
                            <div class="medico-name-cell">
                                <div class="medico-avatar">
                                    {{ substr($medico->nomeMedico, 0, 2) }}
                                </div>
                                <div class="medico-details">
                                    <span class="name">{{ $medico->nomeMedico }}</span>
                                    @if($medico->created_at >= now()->subDays(7))
                                        <span class="badge-new">Novo</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="crm-badge">{{ $medico->crmMedico }}</span>
                        </td>
                        <td>{{ optional($medico->usuario)->emailUsuario ?? 'Sem email' }}</td>
                        <td>
                            @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                                <span class="status-badge status-ativo">
                                    <i class="bi bi-circle-fill"></i> Ativo
                                </span>
                            @else
                                <span class="status-badge status-inativo">
                                    <i class="bi bi-circle-fill"></i> Inativo
                                </span>
                            @endif
                        </td>
                        <td class="actions">
                            <button onclick="quickView({{ $medico->idMedicoPK }})" class="btn-action btn-view" title="Visualizar Rápido">
                                <i class="bi bi-eye"></i>
                            </button>
                            
                            <a href="{{ route('unidade.medicos.edit', $medico->idMedicoPK) }}" class="btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($medico->usuario)
                                <button onclick="openStatusModal('{{ $medico->idMedicoPK }}', '{{ $medico->nomeMedico }}', {{ optional($medico->usuario)->statusAtivoUsuario }})" 
                                        class="btn-action btn-toggle" 
                                        title="{{ optional($medico->usuario)->statusAtivoUsuario == 1 ? 'Desativar' : 'Ativar' }}">
                                    @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                                        <i class="bi bi-toggle-on text-success"></i>
                                    @else
                                        <i class="bi bi-toggle-off text-danger"></i>
                                    @endif
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($medicos->isEmpty())
                        <tr data-status="empty-list">
                            <td colspan="6" class="no-doctors">
                                <i class="bi bi-inbox"></i>
                                <p>Nenhum médico cadastrado.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- VISUALIZAÇÃO EM CARDS -->
        <div class="grid-view" id="gridView" style="display: none;">
            @foreach ($medicos as $medico)
            <div class="medico-card" data-status="{{ optional($medico->usuario)->statusAtivoUsuario == 1 ? 'ativo' : 'inativo' }}" data-id="{{ $medico->idMedicoPK }}">
                <div class="card-header">
                    <input type="checkbox" class="select-medico" value="{{ $medico->idMedicoPK }}" onchange="updateBulkActions()">
                    @if($medico->created_at >= now()->subDays(7))
                        <span class="badge-new">Novo</span>
                    @endif
                </div>
                
                <div class="card-avatar">
                    {{ substr($medico->nomeMedico, 0, 2) }}
                </div>
                
                <h3>{{ $medico->nomeMedico }}</h3>
                <p class="card-crm">CRM: {{ $medico->crmMedico }}</p>
                <p class="card-email">{{ optional($medico->usuario)->emailUsuario ?? 'Sem email' }}</p>
                
                <div class="card-status">
                    @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                        <span class="status-badge status-ativo">
                            <i class="bi bi-circle-fill"></i> Ativo
                        </span>
                    @else
                        <span class="status-badge status-inativo">
                            <i class="bi bi-circle-fill"></i> Inativo
                        </span>
                    @endif
                </div>
                
                <div class="card-actions">
                    <button onclick="quickView({{ $medico->idMedicoPK }})" class="btn-action btn-view">
                        <i class="bi bi-eye"></i>
                    </button>
                    <a href="{{ route('unidade.medicos.edit', $medico->idMedicoPK) }}" class="btn-action btn-edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @if($medico->usuario)
                        <button onclick="openStatusModal('{{ $medico->idMedicoPK }}', '{{ $medico->nomeMedico }}', {{ optional($medico->usuario)->statusAtivoUsuario }})" class="btn-action btn-toggle">
                            @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                                <i class="bi bi-toggle-on text-success"></i>
                            @else
                                <i class="bi bi-toggle-off text-danger"></i>
                            @endif
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-container">
            @if(method_exists($medicos, 'links'))
                {{ $medicos->links() }}
            @endif
        </div>
    </div>
</main>

{{-- MODAL DE VISUALIZAÇÃO RÁPIDA --}}
<div id="quickViewModal" class="modal-overlay">
    <div class="modal-content modal-large">
        <button class="modal-close" onclick="closeQuickView()">
            <i class="bi bi-x"></i>
        </button>
        
        <div class="modal-header">
            <i class="bi bi-eye"></i>
            <h2>Visualização Rápida</h2>
        </div>
        
        <div id="quickViewContent" class="quick-view-content">
            <div class="loading-spinner">
                <i class="bi bi-hourglass-split"></i> Carregando...
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE ALTERAÇÃO DE STATUS --}}
<div id="statusMedicoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-toggle-on"></i>
            <h2>Alterar Status</h2>
        </div>
        
        <p>Tem certeza que deseja <span id="statusAction"></span> o(a) médico(a) <strong><span id="statusMedicoNome"></span></strong>?</p>

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
    // ===== VARIÁVEIS GLOBAIS =====
    let currentView = 'list';
    let selectedMedicos = new Set();

    // ===== VISUALIZAÇÃO (LISTA/CARDS) =====
    function changeView(view) {
        currentView = view;
        const listView = document.getElementById('listView');
        const gridView = document.getElementById('gridView');
        const buttons = document.querySelectorAll('.view-btn');
        
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.view === view) {
                btn.classList.add('active');
            }
        });
        
        if (view === 'list') {
            listView.style.display = 'block';
            gridView.style.display = 'none';
        } else {
            listView.style.display = 'none';
            gridView.style.display = 'grid';
        }
    }

    // ===== SELEÇÃO MÚLTIPLA =====
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.select-medico');
        
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            const card = checkbox.closest('.medico-card');
            
            if ((row && row.style.display !== 'none') || (card && card.style.display !== 'none')) {
                checkbox.checked = selectAll.checked;
                if (selectAll.checked) {
                    selectedMedicos.add(checkbox.value);
                } else {
                    selectedMedicos.delete(checkbox.value);
                }
            }
        });
        
        updateBulkActions();
    }

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.select-medico:checked');
        selectedMedicos.clear();
        
        checkboxes.forEach(cb => selectedMedicos.add(cb.value));
        
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        if (selectedMedicos.size > 0) {
            bulkActions.style.display = 'flex';
            selectedCount.textContent = selectedMedicos.size;
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function clearSelection() {
        selectedMedicos.clear();
        document.querySelectorAll('.select-medico').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkActions();
    }

    function bulkActivate() {
        if (selectedMedicos.size === 0) return;
        alert(`Ativando ${selectedMedicos.size} médico(s)...\n\nImplementar lógica no backend com rota POST enviando array de IDs.`);
    }

    function bulkDeactivate() {
        if (selectedMedicos.size === 0) return;
        alert(`Desativando ${selectedMedicos.size} médico(s)...\n\nImplementar lógica no backend com rota POST enviando array de IDs.`);
    }

    // ===== VISUALIZAÇÃO RÁPIDA =====
    function quickView(medicoId) {
        const modal = document.getElementById('quickViewModal');
        const content = document.getElementById('quickViewContent');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading-spinner"><i class="bi bi-hourglass-spin"></i> Carregando...</div>';
        
        // Simular carregamento de dados (substituir por fetch real)
        setTimeout(() => {
            content.innerHTML = `
                <div class="quick-view-grid">
                    <div class="quick-view-header">
                        <div class="quick-view-avatar-large">DR</div>
                        <div class="quick-view-info">
                            <h3>Dr. Richard Rezende Jr.</h3>
                            <p><i class="bi bi-card-text"></i> <strong>CRM:</strong> 55785/GO</p>
                            <p><i class="bi bi-envelope"></i> <strong>Email:</strong> pvelasques@example.com</p>
                            <p><i class="bi bi-circle-fill text-success"></i> <strong>Status:</strong> Ativo</p>
                            <p><i class="bi bi-calendar-plus"></i> <strong>Cadastrado em:</strong> 15/10/2024</p>
                        </div>
                    </div>
                    
                    <div class="quick-view-stats">
                        <div class="stat-item">
                            <i class="bi bi-calendar-check"></i>
                            <span class="stat-number">45</span>
                            <p>Consultas Realizadas</p>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-clock-history"></i>
                            <span class="stat-number">230h</span>
                            <p>Total de Horas</p>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-star-fill"></i>
                            <span class="stat-number">4.8</span>
                            <p>Avaliação Média</p>
                        </div>
                    </div>
                    
                    <div class="quick-view-actions">
                        <a href="/unidade/medicos/edit/${medicoId}" class="btn-quick-edit">
                            <i class="bi bi-pencil"></i> Editar Perfil Completo
                        </a>
                    </div>
                </div>
            `;
        }, 800);
    }

    function closeQuickView() {
        document.getElementById('quickViewModal').style.display = 'none';
    }

    // ===== EXPORTAR PARA EXCEL =====
    function exportToExcel() {
        alert('Exportando para Excel...\n\nImplementar no backend:\n- Rota GET /unidade/medicos/export\n- Usar Laravel Excel (maatwebsite/excel)\n- Retornar arquivo .xlsx com filtros aplicados');
    }

    // ===== ORDENAÇÃO =====
    let sortDirection = {};
    function sortTable(column) {
        alert(`Ordenando por ${column}...\n\nImplementar ordenação via JavaScript ou AJAX com backend.`);
    }

    // ===== FILTROS =====
    function filterMedicos() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;
        
        // Filtrar tabela
        document.querySelectorAll('#listView tbody tr').forEach(row => {
            if (row.dataset.status === 'empty-list') return;
            
            const name = row.querySelector('.medico-name-cell .name')?.textContent.toLowerCase() || '';
            const crm = row.querySelector('.crm-badge')?.textContent.toLowerCase() || '';
            const email = row.children[3]?.textContent.toLowerCase() || '';
            const status = row.dataset.status;
            
            const matchesSearch = name.includes(searchInput) || crm.includes(searchInput) || email.includes(searchInput);
            const matchesStatus = !filterStatus || status === filterStatus;
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
        
        // Filtrar cards
        document.querySelectorAll('.medico-card').forEach(card => {
            const name = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const crm = card.querySelector('.card-crm')?.textContent.toLowerCase() || '';
            const email = card.querySelector('.card-email')?.textContent.toLowerCase() || '';
            const status = card.dataset.status;
            
            const matchesSearch = name.includes(searchInput) || crm.includes(searchInput) || email.includes(searchInput);
            const matchesStatus = !filterStatus || status === filterStatus;
            
            card.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
        });
    }

    // ===== MODAL DE STATUS =====
    function openStatusModal(medicoId, medicoNome, currentStatus) {
        const modal = document.getElementById('statusMedicoModal');
        const form = document.getElementById('statusMedicoForm');
        const action = currentStatus == 1 ? 'desativar' : 'ativar';
        
        document.getElementById('statusMedicoNome').textContent = medicoNome;
        document.getElementById('statusAction').textContent = action;
        document.getElementById('confirmStatusText').textContent = action;
        
        form.action = form.action.replace(':id:', medicoId);
        modal.style.display = 'flex';
    }

    function closeStatusModal() {
        document.getElementById('statusMedicoModal').style.display = 'none';
    }

    // ===== MODAL DE SUCESSO =====
    function openSuccessModal(message) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('statusSuccessModal').style.display = 'flex';
    }

    function closeSuccessModal() {
        document.getElementById('statusSuccessModal').style.display = 'none';
        window.location.reload();
    }

    // ===== CUSTOM SELECT =====
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
                const icon = selected.querySelector('i').outerHTML;
                selected.innerHTML = `${icon} ${option.textContent}`;
                hiddenInput.value = option.dataset.value;
                customSelect.classList.remove('active');
                filterMedicos();
            });
        });

        document.addEventListener("click", () => {
            customSelect.classList.remove('active');
        });
    }

    // ===== EVENT LISTENERS =====
    document.addEventListener("DOMContentLoaded", () => {
        initializeCustomSelect("customStatus");
        
        @if(session('success'))
            openSuccessModal("{{ session('success') }}");
        @endif
    });

    document.getElementById('statusMedicoModal').addEventListener('click', (e) => {
        if (e.target.id === 'statusMedicoModal') closeStatusModal();
    });

    document.getElementById('quickViewModal').addEventListener('click', (e) => {
        if (e.target.id === 'quickViewModal') closeQuickView();
    });

    document.getElementById('statusSuccessModal').addEventListener('click', (e) => {
        if (e.target.id === 'statusSuccessModal') closeSuccessModal();
    });
</script>
@endsection