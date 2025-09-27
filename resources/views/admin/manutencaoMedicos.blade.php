@extends('admin.templates.admTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/manutencaoMedicos.css') }}">

@php $admin = auth()->guard('admin')->user(); @endphp

<main class="main-dashboard">
  <div class="medico-container">
    <div class="medico-header">
      <h1><i class="bi bi-person-vcard-fill"></i> Gerenciamento de Médicos</h1>
      <a href="{{ route('admin.medicos.create') }}" class="btn-add-medico">
        <i class="bi bi-plus-circle"></i> Cadastrar Médico
      </a>

    </div>

    <div class="search-filters">
      <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Pesquisar por nome, CRM ou email..." onkeyup="filterPatients()">
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
            <td>{{ $medico->usuario->emailUsuario ?? 'Sem email' }}</td>
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
              <form action="{{ route('admin.medicos.toggleStatus', $medico->idMedicoPK) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none;">
                  @if($medico->usuario->statusAtivoUsuario == 1)
                  <i class="bi bi-slash-circle text-danger" title="Desativar"></i>
                  @else
                  <i class="bi bi-check-circle text-success" title="Ativar"></i>
                  @endif
                </button>
              </form>
              @endif

              {{-- CHAMADA JS PARA ABRIR O MODAL --}}
              <a href="#" onclick="openDeleteModal('{{ $medico->idMedicoPK }}', '{{ $medico->nomeMedico }}')">
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

{{-- ESTRUTURA DO MODAL DE EXCLUSÃO --}}
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="bi bi-trash-fill"></i>
            <h2>Excluir Médico</h2>
        </div>
        
        <p>Tem certeza que deseja excluir o médico <span id="medicoNome"></span>?</p>

        {{-- O action será preenchido pelo JavaScript --}}
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="modal-buttons">
                <button type="button" onclick="closeDeleteModal()" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-excluir">Sim, excluir</button>
            </div>
        </form>
    </div>
</div>


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

// ===============================================
// FUNÇÕES DO MODAL DE EXCLUSÃO (NOVO CÓDIGO)
// ===============================================
function openDeleteModal(medicoId, medicoNome) {
    const modal = document.getElementById('deleteModal');
    const nomeSpan = document.getElementById('medicoNome');
    const form = document.getElementById('deleteForm');

    nomeSpan.textContent = medicoNome;

    const deleteRoute = "{{ route('admin.medicos.excluir', ['id' => 'PLACEHOLDER_ID']) }}";
    form.action = deleteRoute.replace('PLACEHOLDER_ID', medicoId);
    
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

document.getElementById('deleteModal').addEventListener('click', function(event) {
    if (event.target.id === 'deleteModal') {
        closeDeleteModal();
    }
});
</script>

@endsection