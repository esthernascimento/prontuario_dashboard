@extends('medico.templates.medicoTemplate')

@section('title', 'Prontuário dos Pacientes')

@section('content')
<link rel="stylesheet" href="{{ asset('css/medico/MedicoProntuario.css') }}">

<main class="main-dashboard">
  <div class="enfermeiro-container">
    <div class="enfermeiro-header">
      <h1><i class="bi bi-journal-medical"></i> Prontuário dos Pacientes</h1>
    </div>

    <div class="search-filters">
      <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF..." onkeyup="filterPatients()">
      </div>

      <div class="filters">
        <div class="custom-select" id="customStatus">
          <div class="selected">Status</div>
          <div class="options">
            <div data-value="">Todos</div>
            <div data-value="1">Ativo</div>
            <div data-value="0">Inativo</div>
          </div>
        </div>
        <input type="hidden" id="filterStatus" value="">
      </div>
    </div>
    
    <div class="box-table">
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Nascimento</th>
            <th>Status</th>
            <th>Prontuário</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pacientes as $paciente)
          <tr data-status="{{ $paciente->statusPaciente ? '1' : '0' }}" 
              data-name="{{ strtolower($paciente->nomePaciente) }}" 
              data-cpf="{{ $paciente->cpfPaciente }}">
            <td>{{ $paciente->nomePaciente }}</td>
            <td>{{ $paciente->cpfPaciente }}</td>
            <td>{{ $paciente->dataNascPaciente ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') : 'N/A' }}</td>
            <td>
              @if($paciente->statusPaciente)
                <span style="color: #0a400c;">Ativo</span>
              @else
                <span style="color: red;">Inativo</span>
              @endif
            </td>
            <td class="actions">
              <a href="{{ route('medico.paciente.prontuario', $paciente->idPaciente) }}" title="Visualizar Prontuário">
                <i class="bi bi-eye-fill"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align: center;">Nenhum paciente encontrado.</td>
          </tr>
          @endforelse
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
      // Pula a linha de "nenhum paciente"
      if (!row.dataset.name) return;

      const name = row.dataset.name;
      const cpf = row.dataset.cpf;
      const status = row.dataset.status;

      const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput);
      const matchesStatus = !filterStatus || status === filterStatus;

      row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
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
</script>
@endsection