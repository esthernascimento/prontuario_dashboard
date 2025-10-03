@extends('geral.templates.geralTemplate')

@section('content')
  @php
    $admin = auth()->guard('admin')->user();

    use App\Models\Paciente;
    // Se o controller não passar $pacientes, pega todos do Model
    $pacientes = $pacientes ?? Paciente::paginate(10);
  @endphp

  <main class="main-dashboard">
    <div class="patients-container">
      <div class="patients-header">
        <h1><i class="bi bi-people-fill"></i> Gerenciamento de Pacientes</h1>
        <a href="{{ route('admin.cadastroPaciente') }}" class="btn-add-paciente">
          <i class="bi bi-plus-circle"></i> Cadastrar Paciente
        </a>
      </div>

      <!-- Barra de pesquisa e filtros -->
      <div class="search-filters">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF ou telefone..." onkeyup="filterPatients()">
        </div>
        <div class="custom-select" id="customAge">
          <div class="selected">Todas as idades</div>
          <div class="options">
            <div data-value="">Todas as idades</div>
            <div data-value="crianca">Crianças (0-12)</div>
            <div data-value="adolescente">Adolescentes (13-17)</div>
            <div data-value="adulto">Adultos (18-59)</div>
            <div data-value="idoso">Idosos (60+)</div>
          </div>
        </div>
        <input type="hidden" id="filterAge" value="">

        <div class="custom-select" id="customGender">
          <div class="selected">Todos os gêneros</div>
          <div class="options">
            <div data-value="">Todos os gêneros</div>
            <div data-value="M">Masculino</div>
            <div data-value="F">Feminino</div>
          </div>
        </div>
        <input type="hidden" id="filterGender" value="">
      </div>

      <!-- Tabela de Pacientes -->
      <table class="patients-table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Idade</th>
            <th>Gênero</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($pacientes) && count($pacientes) > 0)
            @foreach($pacientes as $paciente)
              <tr data-age-group="{{ $paciente->faixa_etaria }}" data-gender="{{ $paciente->genero }}">
                <td>{{ $paciente->nome }}</td>
                <td>{{ $paciente->cpf }}</td>
                <td>{{ $paciente->telefone }}</td>
                <td>{{ $paciente->idade }}</td>
                <td>{{ $paciente->genero }}</td>
                <td>
                  <a href="{{ route('admin.paciente.show', $paciente->id) }}" class="btn-view">Ver</a>
                  <a href="{{ route('admin.paciente.edit', $paciente->id) }}" class="btn-edit">Editar</a>
                  <form action="{{ route('admin.paciente.destroy', $paciente->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">Excluir</button>
                  </form>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6">Nenhum paciente encontrado.</td>
            </tr>
          @endif
        </tbody>
      </table>

      <!-- Paginação -->
      <div class="pagination-container">
        @if(method_exists($pacientes, 'links'))
          {{ $pacientes->links() }}
        @endif
      </div>
    </div>
  </main>

  <script>
    // Inicializa a função de filtro de pacientes
    function filterPatients() {
      const searchInput = document.getElementById('searchInput').value.toLowerCase();
      const filterAge = document.getElementById('filterAge').value;
      const filterGender = document.getElementById('filterGender').value;

      const rows = document.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const name = row.children[0].textContent.toLowerCase();
        const cpf = row.children[1].textContent.toLowerCase();
        const phone = row.children[2].textContent.toLowerCase();
        const ageGroup = row.dataset.ageGroup; 
        const gender = row.dataset.gender;

        const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput) || phone.includes(searchInput);
        const matchesAge = !filterAge || ageGroup === filterAge;
        const matchesGender = !filterGender || gender === filterGender;

        if (matchesSearch && matchesAge && matchesGender) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // Inicializa os seletores personalizados
    function initializeCustomSelect(containerId) {
      const customSelect = document.getElementById(containerId);
      const selected = customSelect.querySelector(".selected");
      const options = customSelect.querySelector(".options");
      const hiddenInput = document.getElementById(containerId.replace('custom', 'filter'));

      selected.addEventListener("click", () => {
        document.querySelectorAll(".custom-select .options").forEach(opt => {
          if (opt !== options) {
            opt.style.display = "none";
          }
        });
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
    }

    document.addEventListener("DOMContentLoaded", () => {
      initializeCustomSelect("customAge");
      initializeCustomSelect("customGender");
    });
  </script>
@endsection
