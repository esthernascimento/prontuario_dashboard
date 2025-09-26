@extends('admin.templates.admTemplate')

@section('title', 'Manutenção de Enfermeiros')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/manutencaoEnfermeiros.css') }}">

    @php $admin = auth()->guard('enfermeiro')->user(); @endphp


    @if(session('success'))
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
                            <form action="{{ route('admin.enfermeiros.toggleStatus', $enfermeiro->idEnfermeiroPK) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none;">
                                @if($enfermeiro->usuario->statusAtivoUsuario == 1)
                                <i class="bi bi-slash-circle text-danger" title="Desativar"></i>
                                @else
                                <i class="bi bi-check-circle text-success" title="Ativar"></i>
                                @endif
                            </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.enfermeiro.confirmarExclusao', $enfermeiro->idEnfermeiroPK) }}">
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