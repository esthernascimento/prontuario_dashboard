@extends('admin.templates.admTemplate')

@section('title', 'Gerenciamento de Unidades')

@section('content')
{{-- Reutilizando o mesmo CSS para manter a consistência visual --}}
<link rel="stylesheet" href="{{ asset('css/admin/manutencaoUnidade.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
    <div class="medico-container"> {{-- Reutilizando a classe de layout --}}
        <div class="medico-header">
            <h1><i class="bi bi-hospital-fill"></i> Gerenciamento de Unidades</h1>
            <a href="{{ route('admin.unidades.create') }}" class="btn-add-medico">
                <i class="bi bi-plus-circle"></i> Cadastrar Unidade
            </a>
        </div>

        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, tipo ou endereço..." onkeyup="filterUnidades()">
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
                        <th>Nome da Unidade</th>
                        <th>Tipo</th>
                        <th>Endereço</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unidades as $unidade)
                    <tr data-status="{{ $unidade->trashed() ? 'inativo' : 'ativo' }}">
                        <td>{{ $unidade->nomeUnidade }}</td>
                        <td>{{ $unidade->tipoUnidade }}</td>
                        {{-- Combinando os campos de endereço para exibição --}}
                        <td>{{ $unidade->logradouroUnidade }}, {{ $unidade->numLogradouroUnidade }} - {{ $unidade->cidadeUnidade }}</td>
                        <td>
                            @if(!$unidade->trashed())
                                <span class="status-badge status-ativo">Ativo</span>
                            @else
                                <span class="status-badge status-inativo">Inativo</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.unidades.edit', $unidade->idUnidadePK) }}" class="btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="#" onclick="openStatusModal('{{ $unidade->idUnidadePK }}', '{{ $unidade->nomeUnidade }}', {{ $unidade->trashed() ? 0 : 1 }})" class="btn-action" title="{{ !$unidade->trashed() ? 'Desativar' : 'Ativar' }}">
                                @if(!$unidade->trashed())
                                    <i class="bi bi-slash-circle text-danger"></i>
                                @else
                                    <i class="bi bi-check-circle text-success"></i>
                                @endif
                            </a>

                            {{-- REMOVEMOS O BOTÃO DE EXCLUIR PERMANENTEMENTE --}}
                        </td>
                    </tr>
                    @empty
                        <tr data-status="empty-list">
                            <td colspan="5" class="no-doctors">Nenhuma unidade cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            @if(method_exists($unidades, 'links'))
                {{ $unidades->links() }}
            @endif
        </div>
    </div>
</main>

{{-- O MODAL DE EXCLUSÃO FOI REMOVIDO, POIS NÃO É MAIS NECESSÁRIO. --}}

{{-- MODAL DE ALTERAÇÃO DE STATUS --}}
<div id="statusModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header"><i class="bi bi-toggle-on"></i><h2>Alterar Status</h2></div>
        <p>Tem certeza que deseja <span id="statusAction"></span> a unidade <span id="statusNome"></span>?</p>
        <form id="statusForm" method="POST">
            @csrf
            <div class="modal-buttons">
                <button type="button" onclick="closeStatusModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, <span id="confirmStatusText"></span></button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE SUCESSO UNIFICADO --}}
<div id="successModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header"><i class="bi bi-check-circle-fill"></i><h2>Sucesso!</h2></div>
        <p id="successMessage"></p>
        <div class="modal-buttons">
            <button type="button" onclick="closeSuccessModal()" class="btn-excluir">Fechar</button>
        </div>
    </div>
</div>

<script>
    // LÓGICA DOS MODAIS
    function openSuccessModal(message) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('successModal').style.display = 'flex';
    }
    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        window.location.reload(); 
    }
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => openSuccessModal("{{ session('success') }}"));
    @endif

    // As funções openDeleteModal e closeDeleteModal não são mais necessárias.
    // O código a seguir já estava em sua view e está adaptado para a nova lógica.
    
    function openStatusModal(id, nome, currentStatus) {
        const action = currentStatus == 1 ? 'desativar' : 'ativar';
        document.getElementById('statusNome').textContent = nome;
        document.getElementById('statusAction').textContent = action;
        document.getElementById('confirmStatusText').textContent = action;
        const form = document.getElementById('statusForm');
        let url = "{{ route('admin.unidades.toggleStatus', ['id' => ':id']) }}";
        form.action = url.replace(':id', id);
        document.getElementById('statusModal').style.display = 'flex';
    }
    function closeStatusModal() { document.getElementById('statusModal').style.display = 'none'; }

    // Fechamento dos modais clicando fora
    ['statusModal', 'successModal'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', function(event) {
            if (event.target.id === id) {
                if (id === 'statusModal') closeStatusModal();
                if (id === 'successModal') closeSuccessModal();
            }
        });
    });

    // LÓGICA DE FILTRAGEM
    function filterUnidades() {
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
            const nome = row.children[0].textContent.toLowerCase();
            const tipo = row.children[1].textContent.toLowerCase();
            const endereco = row.children[2].textContent.toLowerCase();
            const status = row.dataset.status;
            const matchesSearch = nome.includes(searchInput) || tipo.includes(searchInput) || endereco.includes(searchInput);
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
                filterUnidades();
            });
        });

        document.addEventListener("click", () => {
            customSelect.classList.remove('active');
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeCustomSelect("customStatus");
        document.getElementById('searchInput').addEventListener('input', filterUnidades);
    });
</script>
@endsection