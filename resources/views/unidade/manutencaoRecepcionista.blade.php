@extends('unidade.templates.unidadeTemplate')

@section('title', 'Manutenção de Recepcionistas')

@section('content')
<link rel="stylesheet" href="{{ asset('css/unidade/manutencaoRecepcionista.css') }}">

@php $unidade = auth()->guard('unidade')->user(); @endphp

<main class="main-dashboard">
    <div class="recepcionista-container">
        <div class="recepcionista-header">
            <h1><i class="bi bi-person-vcard"></i> Gerenciamento de Recepcionistas</h1>
            <a href="{{ route('unidade.recepcionistas.create') }}" class="btn-add-recepcionista">
                <i class="bi bi-plus-circle"></i> Cadastrar Recepcionista
            </a>
        </div>

        <div class="search-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome ou email..." onkeyup="filterRecepcionistas()">
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
                    @foreach ($recepcionistas as $recepcionista)
                    <tr data-status="{{ $recepcionista->statusAtivoRecepcionista == 1 ? 'ativo' : 'inativo' }}">
                        <td>{{ $recepcionista->nomeRecepcionista }}</td>
                        <td>{{ $recepcionista->emailRecepcionista }}</td>
                        <td>
                            @if($recepcionista->statusAtivoRecepcionista == 1)
                                <span class="status-badge status-ativo">Ativo</span>
                            @else
                                <span class="status-badge status-inativo">Inativo</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('unidade.recepcionistas.edit', $recepcionista->idRecepcionistaPK) }}" class="btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="#" onclick="openStatusModal('{{ $recepcionista->idRecepcionistaPK }}', '{{ $recepcionista->nomeRecepcionista }}', {{ $recepcionista->statusAtivoRecepcionista }})" class="btn-action" title="{{ $recepcionista->statusAtivoRecepcionista == 1 ? 'Desativar' : 'Ativar' }}">
                                @if($recepcionista->statusAtivoRecepcionista == 1)
                                    <i class="bi bi-slash-circle text-danger"></i>
                                @else
                                    <i class="bi bi-check-circle text-success"></i>
                                @endif
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
                            <td colspan="4" class="no-recepcionistas">Nenhum recepcionista cadastrado.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            @if(method_exists($recepcionistas, 'links'))
                {{ $recepcionistas->links() }}
            @endif
        </div>
    </div>
</main>

{{-- Modais e JavaScript mantidos similares --}}
<script>
    function filterRecepcionistas() {
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
            const email = row.children[1].textContent.toLowerCase();
            const status = row.dataset.status;

            const matchesSearch = name.includes(searchInput) || email.includes(searchInput);
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

    // Restante do JavaScript similar ao original
    document.addEventListener("DOMContentLoaded", () => {
        initializeCustomSelect("customStatus");
        document.getElementById('searchInput').addEventListener('input', filterRecepcionistas);
    });
</script>
@endsection