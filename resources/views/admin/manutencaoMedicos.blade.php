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

                  <a href="{{ route('admin.medicos.confirmarExclusao', $medico->idMedicoPK) }}">
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

    // abre/fecha dropdown
    selected.addEventListener("click", () => {
      options.style.display = options.style.display === "flex" ? "none" : "flex";
    });

    // ao selecionar opção
    options.querySelectorAll("div").forEach(option => {
      option.addEventListener("click", () => {
        selected.textContent = option.textContent;
        hiddenInput.value = option.dataset.value;
        options.style.display = "none";
        filterPatients(); // chama sua função existente
      });
    });

    // fecha clicando fora
    document.addEventListener("click", e => {
      if (!customSelect.contains(e.target)) {
        options.style.display = "none";
      }
    });



  </script>

@endsection
