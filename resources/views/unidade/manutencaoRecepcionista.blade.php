@extends('unidade.templates.unidadeTemplate')

@section('title', 'Gerenciamento de Recepcionistas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/unidade/manutencaoRecepcionista.css') }}">

@php $unidade = auth()->guard('unidade')->user(); @endphp

<main class="main-dashboard">
    <div class="recepcionista-container">
        <!-- DASHBOARD DE MÉTRICAS -->
        <div class="metrics-dashboard">
            <div class="metric-card total">
                <div class="metric-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">TOTAL DE RECEPCIONISTAS</span>
                    <span class="metric-value">{{ $recepcionistas->count() }}</span>
                </div>
            </div>

            <div class="metric-card active">
                <div class="metric-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">ATIVOS</span>
                    <span class="metric-value">{{ $recepcionistas->where('statusAtivoRecepcionista', 1)->count() }}</span>
                </div>
            </div>

            <div class="metric-card inactive">
                <div class="metric-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">INATIVOS</span>
                    <span class="metric-value">{{ $recepcionistas->where('statusAtivoRecepcionista', 0)->count() }}</span>
                </div>
            </div>

            <div class="metric-card new">
                <div class="metric-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">NOVOS ESTE MÊS</span>
                    <span class="metric-value">{{ $recepcionistas->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                </div>
            </div>
        </div>

        <!-- HEADER COM TÍTULO E AÇÕES -->
        <div class="recepcionista-header">
            <div class="header-title">
                <h1><i class="bi bi-person-vcard"></i> Gerenciamento de Recepcionistas</h1>
                <span class="subtitle">Gerencie todos os recepcionistas da unidade</span>
            </div>
            
            <div class="header-actions">
                <button onclick="exportToExcel()" class="btn-export" title="Exportar para Excel">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Exportar
                </button>
                <a href="{{ route('unidade.recepcionistas.create') }}" class="btn-add-recepcionista">
                    <i class="bi bi-plus-circle"></i> Cadastrar Recepcionista
                </a>
            </div>
        </div>

        <!-- FILTROS AVANÇADOS -->
        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome ou email..." onkeyup="filterRecepcionistas()">
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

        <!-- VISUALIZAÇÃO EM LISTA -->
        <div class="box-table" id="listView">
            <table>
                <thead>
                    <tr>
                        <th onclick="sortTable('nome')" style="cursor: pointer;">
                            NOME RECEPCIONISTA <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recepcionistas as $recepcionista)
                    <tr data-status="{{ $recepcionista->statusAtivoRecepcionista == 1 ? 'ativo' : 'inativo' }}" 
                        class="{{ $recepcionista->created_at >= now()->subDays(7) ? 'new-entry' : '' }}">
                        <td>
                            <div class="recepcionista-name-cell">
                                <div class="recepcionista-avatar">
                                    {{ substr($recepcionista->nomeRecepcionista, 0, 2) }}
                                </div>
                                <div class="recepcionista-details">
                                    <span class="name">{{ $recepcionista->nomeRecepcionista }}</span>
                                    @if($recepcionista->created_at >= now()->subDays(7))
                                        <span class="badge-new">Novo</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $recepcionista->emailRecepcionista }}</td>
                        <td>
                            @if($recepcionista->statusAtivoRecepcionista == 1)
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
                            <button onclick="quickView({{ $recepcionista->idRecepcionistaPK }})" class="btn-action btn-view" title="Visualizar Rápido">
                                <i class="bi bi-eye"></i>
                            </button>
                            
                            <a href="{{ route('unidade.recepcionistas.edit', $recepcionista->idRecepcionistaPK) }}" class="btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('unidade.recepcionistas.destroy', $recepcionista->idRecepcionistaPK) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este recepcionista?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($recepcionistas->isEmpty())
                        <tr data-status="empty-list">
                            <td colspan="4" class="no-recepcionistas">
                                <i class="bi bi-inbox"></i>
                                <p>Nenhum recepcionista cadastrado.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- VISUALIZAÇÃO EM CARDS -->
        <div class="grid-view" id="gridView" style="display: none;">
            @foreach ($recepcionistas as $recepcionista)
            <div class="recepcionista-card" data-status="{{ $recepcionista->statusAtivoRecepcionista == 1 ? 'ativo' : 'inativo' }}">
                <div class="card-header">
                    @if($recepcionista->created_at >= now()->subDays(7))
                        <span class="badge-new">Novo</span>
                    @endif
                </div>
                
                <div class="card-avatar">
                    {{ substr($recepcionista->nomeRecepcionista, 0, 2) }}
                </div>
                
                <h3>{{ $recepcionista->nomeRecepcionista }}</h3>
                <p class="card-email">{{ $recepcionista->emailRecepcionista }}</p>
                
                <div class="card-status">
                    @if($recepcionista->statusAtivoRecepcionista == 1)
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
                    <button onclick="quickView({{ $recepcionista->idRecepcionistaPK }})" class="btn-action btn-view">
                        <i class="bi bi-eye"></i>
                    </button>
                    <a href="{{ route('unidade.recepcionistas.edit', $recepcionista->idRecepcionistaPK) }}" class="btn-action btn-edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('unidade.recepcionistas.destroy', $recepcionista->idRecepcionistaPK) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este recepcionista?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-container">
            @if(method_exists($recepcionistas, 'links'))
                {{ $recepcionistas->links() }}
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

    // ===== VISUALIZAÇÃO RÁPIDA =====
    function quickView(recepcionistaId) {
        const modal = document.getElementById('quickViewModal');
        const content = document.getElementById('quickViewContent');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading-spinner"><i class="bi bi-hourglass-spin"></i> Carregando...</div>';
        
        // Busca dados do recepcionista via AJAX
        fetch(`/unidade/recepcionistas/${recepcionistaId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            content.innerHTML = `
                <div class="quick-view-grid">
                    <div class="quick-view-header">
                        <div class="quick-view-avatar-large">${data.nome.substring(0, 2).toUpperCase()}</div>
                        <div class="quick-view-info">
                            <h3>${data.nome}</h3>
                            <p><i class="bi bi-envelope"></i> <strong>Email:</strong> ${data.email}</p>
                            <p><i class="bi bi-circle-fill ${data.status == 1 ? 'text-success' : 'text-danger'}"></i> <strong>Status:</strong> ${data.status == 1 ? 'Ativo' : 'Inativo'}</p>
                            <p><i class="bi bi-calendar-plus"></i> <strong>Cadastrado em:</strong> ${data.created_at}</p>
                        </div>
                    </div>
                    
                    <div class="quick-view-stats">
                        <div class="stat-item">
                            <i class="bi bi-calendar-check"></i>
                            <span class="stat-number">${data.atendimentos || 0}</span>
                            <p>Atendimentos Realizados</p>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-clock-history"></i>
                            <span class="stat-number">${data.horas_trabalhadas || 0}h</span>
                            <p>Total de Horas</p>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-star-fill"></i>
                            <span class="stat-number">${data.avaliacao || 'N/A'}</span>
                            <p>Avaliação Média</p>
                        </div>
                    </div>
                    
                    <div class="quick-view-actions">
                        <a href="/unidade/recepcionistas/${recepcionistaId}/edit" class="btn-quick-edit">
                            <i class="bi bi-pencil"></i> Editar Perfil Completo
                        </a>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Erro:', error);
            content.innerHTML = '<p>Erro ao carregar informações do recepcionista.</p>';
        });
    }

    function closeQuickView() {
        document.getElementById('quickViewModal').style.display = 'none';
    }

    // ===== EXPORTAR PARA EXCEL =====
    function exportToExcel() {
        window.location.href = '/unidade/recepcionistas/export';
    }

    // ===== ORDENAÇÃO =====
    let sortDirection = {};
    function sortTable(column) {
        const table = document.querySelector('#listView table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr:not([data-status="empty-list"])'));
        
        // Inverte a direção se a mesma coluna for clicada novamente
        sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';
        
        // Ordena as linhas
        rows.sort((a, b) => {
            let aValue, bValue;
            
            if (column === 'nome') {
                aValue = a.querySelector('.recepcionista-name-cell .name').textContent.trim();
                bValue = b.querySelector('.recepcionista-name-cell .name').textContent.trim();
            } else if (column === 'email') {
                aValue = a.children[1].textContent.trim();
                bValue = b.children[1].textContent.trim();
            } else if (column === 'status') {
                aValue = a.dataset.status;
                bValue = b.dataset.status;
            }
            
            if (sortDirection[column] === 'asc') {
                return aValue.localeCompare(bValue);
            } else {
                return bValue.localeCompare(aValue);
            }
        });
        
        // Reinsere as linhas ordenadas na tabela
        rows.forEach(row => tbody.appendChild(row));
    }

    // ===== FILTROS =====
    function filterRecepcionistas() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;
        
        // Filtrar tabela
        document.querySelectorAll('#listView tbody tr').forEach(row => {
            if (row.dataset.status === 'empty-list') return;
            
            const name = row.querySelector('.recepcionista-name-cell .name')?.textContent.toLowerCase() || '';
            const email = row.children[1]?.textContent.toLowerCase() || '';
            const status = row.dataset.status;
            
            const matchesSearch = name.includes(searchInput) || email.includes(searchInput);
            const matchesStatus = !filterStatus || status === filterStatus;
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
        
        // Filtrar cards
        document.querySelectorAll('.recepcionista-card').forEach(card => {
            const name = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const email = card.querySelector('.card-email')?.textContent.toLowerCase() || '';
            const status = card.dataset.status;
            
            const matchesSearch = name.includes(searchInput) || email.includes(searchInput);
            const matchesStatus = !filterStatus || status === filterStatus;
            
            card.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
        });
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
                filterRecepcionistas();
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

    document.getElementById('quickViewModal').addEventListener('click', (e) => {
        if (e.target.id === 'quickViewModal') closeQuickView();
    });

    document.getElementById('statusSuccessModal').addEventListener('click', (e) => {
        if (e.target.id === 'statusSuccessModal') closeSuccessModal();
    });
</script>
@endsection