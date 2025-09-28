@extends('enfermeiro.templates.enfermeiroTemplate')

@section('content')

<link rel="stylesheet" href="{{ asset('css/enfermeiro/pacientes.css') }}">

  @php $admin = auth()->guard('admin')->user(); 

  @endphp

  <main class="main-dashboard">
    <div class="patients-container">
      <div class="patients-header">
        <h1><i class="bi bi-people-fill">

          </i> Gerenciamento de Pacientes</h1>
          <a href="#" class="btn-add-paciente">
              <i class="bi bi-plus-circle"></i> Cadastrar Paciente
            </a>
      </div>
      <!-- Barra de pesquisa e filtros -->
      <div class="search-filters">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF ou telefone..."
            onkeyup="filterPatients()">
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

        // Garante que o script é executado após o carregamento do DOM
        document.addEventListener("DOMContentLoaded", () => {
            initializeCustomSelect("customAge");
            initializeCustomSelect("customGender");
        });
      </script>
@endsection