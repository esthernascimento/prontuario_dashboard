@extends('admin.templates.admTemplate')

@section('title', 'Gerenciamento de Médicos')

@section('content')

{{-- 
    IMPORTANTE: Assuma-se que você tenha um arquivo CSS dedicado para 
    manutenção de médicos ou que você usará o mesmo arquivo de enfermeiros
    caso os estilos sejam idênticos.
--}}
<link rel="stylesheet" href="{{ asset('css/admin/manutencaoMedicos.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

{{-- 
    O bloco de alerta antigo foi removido para que o MODAL DE SUCESSO UNIFICADO 
    abaixo possa gerenciar todas as mensagens de sucesso.
--}}

    <main class="main-dashboard">
        <div class="medico-container">
            <div class="medico-header">
                <h1><i class="bi bi-person-badge-fill"></i> Gerenciamento de Médico</h1>
                <a href="{{ route('admin.medicos.create') }}" class="btn-add-medico">
                <i class="bi bi-plus-circle"></i> Cadastrar Médico
                </a>
            </div>


            <div class="search-filters">
                <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome, CRM ou email..." onkeyup="filterMedicos()">
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
                            <span style="color: green;">Ativo</span>
                        @else
                            <span style="color: red;">Inativo</span>
                        @endif
                        </td>
                        <td class="actions">
                        <a href="{{ route('admin.medicos.editar', $medico->idMedicoPK) }}">
                            <i class="bi bi-pencil" title="Editar"></i>
                        </a>

                        @if($medico->usuario)
                            {{-- Chamada do modal de status --}}
                            <a href="#" onclick="openStatusModal('{{ $medico->idMedicoPK }}', '{{ $medico->nomeMedico }}', {{ optional($medico->usuario)->statusAtivoUsuario }})">
                                @if(optional($medico->usuario)->statusAtivoUsuario == 1)
                                <i class="bi bi-slash-circle text-danger" title="Desativar"></i>
                                @else
                                <i class="bi bi-check-circle text-success" title="Ativar"></i>
                                @endif
                            </a>
                        @endif

                        {{-- CHAMADA JS PARA ABRIR O MODAL DE EXCLUSÃO --}}
                        <a href="#" onclick="openDeleteMedicoModal('{{ $medico->idMedicoPK }}', '{{ $medico->nomeMedico }}')">
                            <i class="bi bi-trash" title="Excluir"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach
                    @if (empty($medicos))
                        <tr data-status="empty-list">
                            <td colspan="5" style="text-align: center; padding: 20px;">Nenhum médico cadastrado.</td>
                        </tr>
                    @endif
                </tbody>
                </table>
            </div>
        </div>
    </main>

{{-- MODAL DE EXCLUSÃO --}}
<div id="deleteMedicoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-trash-fill"></i>
            <h2>Excluir Médico(a)</h2>
        </div>
        
        <p>Tem certeza que deseja excluir o(a) médico(a) <span id="medicoNome"></span>?</p>

        <form id="deleteMedicoForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal-buttons">
                <button type="button" onclick="closeDeleteMedicoModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, excluir</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE ALTERAÇÃO DE STATUS --}}
<div id="statusMedicoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-toggle-on"></i>
            <h2>Alterar Status</h2>
        </div>
        
        <p>Tem certeza que deseja <span id="statusAction"></span> o(a) médico(a) <span id="statusMedicoNome"></span>?</p>

        <form id="statusMedicoForm" method="POST">
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
        // Recarrega a página ou faz uma chamada AJAX para atualizar a lista se necessário
        window.location.reload(); 
    }

    // Fecha o modal de sucesso clicando fora
    document.getElementById('statusSuccessModal').addEventListener('click', function(event) {
        if (event.target.id === 'statusSuccessModal') {
            closeSuccessModal();
        }
    });


    // ------------------------------------------
    // Verificação de Sucesso ao Carregar a Página (AGORA UNIVERSAL PARA session('success'))
    // ------------------------------------------

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            // A mensagem de sucesso é a mesma para todas as ações (sucesso, status, updated, deleted)
            const message = "{{ session('success') }}"; 
            openSuccessModal(message);
        });
    @endif


    // ------------------------------------------
    // LÓGICA DOS MODAIS DE MÉDICO
    // ------------------------------------------

    // Funções do Modal de Exclusão
    function openDeleteMedicoModal(medicoId, medicoNome) {
        const modal = document.getElementById('deleteMedicoModal');
        const nomeSpan = document.getElementById('medicoNome');
        const form = document.getElementById('deleteMedicoForm');

        nomeSpan.textContent = medicoNome;

        // Rota de exclusão para médicos
        const deleteRoute = "{{ route('admin.medicos.excluir', ['id' => 'PLACEHOLDER_ID']) }}";
        form.action = deleteRoute.replace('PLACEHOLDER_ID', medicoId);
        
        modal.style.display = 'flex';
    }

    function closeDeleteMedicoModal() {
        document.getElementById('deleteMedicoModal').style.display = 'none';
    }

    document.getElementById('deleteMedicoModal').addEventListener('click', function(event) {
        if (event.target.id === 'deleteMedicoModal') {
            closeDeleteMedicoModal();
        }
    });

    // Funções do Modal de Status
    function openStatusModal(medicoId, medicoNome, currentStatus) {
        const modal = document.getElementById('statusMedicoModal');
        const nomeSpan = document.getElementById('statusMedicoNome');
        const actionSpan = document.getElementById('statusAction');
        const confirmText = document.getElementById('confirmStatusText');
        const form = document.getElementById('statusMedicoForm');
        
        // Se o status for 1 (ativo), a ação será desativar. Se for 0 (inativo), a ação será ativar.
        const action = currentStatus == 1 ? 'desativar' : 'ativar';
        const confirmAction = currentStatus == 1 ? 'desativar' : 'ativar';

        nomeSpan.textContent = medicoNome;
        actionSpan.textContent = action;
        confirmText.textContent = confirmAction;

        // Rota de toggleStatus para médicos
        const statusRoute = "{{ route('admin.medicos.toggleStatus', ['id' => 'PLACEHOLDER_ID']) }}";
        form.action = statusRoute.replace('PLACEHOLDER_ID', medicoId);
        
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


    // ------------------------------------------
    // LÓGICA DE FILTRAGEM
    // ------------------------------------------

    function filterMedicos() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;

        const rows = document.querySelectorAll('tbody tr');
        let visibleRowsCount = 0;
        let emptyRow = null;

        rows.forEach(row => {
            // Verifica se a linha é a linha de "Nenhum médico cadastrado"
            if (row.dataset.status === 'empty-list') {
                emptyRow = row;
                row.style.display = 'none'; // Esconde por padrão para o cálculo
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
        
        // Lógica para mostrar a linha "Nenhum médico cadastrado" se não houver resultados.
        if (emptyRow) {
            if (visibleRowsCount === 0) {
                 // Verifica se o resultado do filtro e pesquisa é zero, e mostra a linha vazia
                emptyRow.style.display = ''; 
            } else {
                emptyRow.style.display = 'none';
            }
        }
    }

    // Lógica para o Select Personalizado de Status
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
            filterMedicos(); // Chama a função de filtro após a seleção
        });
    });

    document.addEventListener("click", e => {
        if (!customSelect.contains(e.target)) {
            options.style.display = "none";
        }
    });

</script>

@endsection
