@extends('enfermeiro.templates.enfermeiroTemplate')

@section('title', 'Prontuário dos Pacientes')

@section('content')
{{-- Inclua o CSS específico (se houver) --}}

<link rel="stylesheet" href="{{ asset('css/enfermeiro/prontuario.css') }}">
{{-- Você pode precisar de Font Awesome ou Bootstrap Icons, adicione o link aqui, ex: --}}
{{-- <link rel="stylesheet" href="https://www.google.com/search?q=https://cdn.jsdelivr.net/npm/bootstrap-icons%401.11.3/font/bootstrap-icons.min.css"> --}}

<main class="main-dashboard">
<div class="enfermeiro-container">
<div class="enfermeiro-header">
{{-- Ícone bi-journal-medical (deve ser importado via Bootstrap Icons ou similar) --}}
<h1><i class="bi bi-journal-medical"></i> Prontuário dos Pacientes</h1>
{{-- Botão de ação (oculto/não necessário para esta tela) --}}
{{-- <a href="{{ route('enfermeiro.create') }}" class="btn-add-enfermeiro" style="display: none;"> --}}
{{-- <i class="bi bi-plus-circle-fill"></i> Novo Paciente --}}
{{-- </a> --}}
</div>

{{-- Filtros e Busca --}}

<div class="search-filters">
<div class="search-box">
<i class="bi bi-search"></i>
<input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF..." onkeyup="filterPatients()">
</div>

<div class="filters">
<div class="custom-select" id="customStatus">
{{-- Note que o CSS usa .selected-filter-input, vamos adaptar o HTML para isso --}}
<input type="text" readonly class="selected-filter-input selected" value="Status" id="selectedFilterText">
<i class="bi bi-chevron-down select-icon"></i>

  <div class="options">
    <div data-value="">Todos</div>
    <div data-value="1">Ativo</div>
    <div data-value="2">Alta</div> {{-- Adicionado Alta para o filtro --}}
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
<th class="status-header">Status</th>
<th class="actions-header">Ações</th>
</tr>
</thead>
<tbody>
{{-- O Controller deve passar a variável $pacientes (plural) --}}
@forelse ($pacientes as $paciente)
{{-- Adaptação: Assumindo que o status 1 é Ativo e 2 é Alta, use o valor real aqui --}}
@php
// Exemplo de lógica simples para determinar o status e a classe
$statusValue = $paciente->statusPaciente ?? 1; // Assumindo 1 como padrão Ativo
$statusText = $statusValue == 1 ? 'Ativo' : 'Alta';
$statusClass = $statusValue == 1 ? 'status-ativo' : 'status-alta';
@endphp
<tr data-status="{{ $statusValue }}"
data-name="{{ strtolower($paciente->nomePaciente) }}"
data-cpf="{{ $paciente->cpfPaciente }}">
<td>{{ $paciente->nomePaciente }}</td>
<td>{{ $paciente->cpfPaciente }}</td>
<td>{{ $paciente->dataNascPaciente ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') : 'N/A' }}</td>
<td class="status-cell">
<span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
</td>
<td class="actions">
{{-- Ação para Visualizar Prontuário --}}
<a href="{{ route('enfermeiro.prontuario.show', $paciente->idPaciente) }}"
class="btn-action btn-view"
title="Visualizar Prontuário e Histórico">
<i class="bi bi-eye-fill"></i>
</a>
{{-- Ação para Criar NOVO REGISTRO/ANOTAÇÃO --}}
{{-- CLASSE AJUSTADA DE 'btn-add-consulta' PARA 'btn-add-anotacao' PARA EVITAR CONFLITO DE CSS/EFEITO INDESEJADO --}}
<a href="{{ route('enfermeiro.anotacao.create', $paciente->idPaciente) }}"
class="btn-action btn-add-anotacao"
title="Criar Nova Anotação de Enfermagem">
<i class="bi bi-file-earmark-text-fill"></i>
</a>
</td>
</tr>
@empty
<tr>
<td colspan="5" class="no-enfermeiros">Nenhum paciente encontrado.</td>
</tr>
@endforelse
</tbody>
</table>

{{-- Exemplo de Paginação --}}
@if (isset($pacientes) && method_exists($pacientes, 'links'))
<div class="pagination-container">
{{ $pacientes->links() }}
</div>
@endif

</div>

</div>
</main>

{{-- Script de filtro (mantido e adaptado) --}}

<script>
// Função principal de filtragem
function filterPatients() {
const searchInput = document.getElementById('searchInput').value.toLowerCase();
const filterStatusValue = document.getElementById('filterStatus').value;
const rows = document.querySelectorAll('tbody tr');

rows.forEach(row => {
// Ignora a linha de 'Nenhum paciente encontrado'
if (!row.dataset.name) {
row.style.display = 'none';
return;
}

const name = row.dataset.name;
const cpf = row.dataset.cpf;
const status = row.dataset.status;

// 1. Filtro por Pesquisa (Nome ou CPF)
const matchesSearch = name.includes(searchInput) || cpf.includes(searchInput);

// 2. Filtro por Status
const matchesStatus = filterStatusValue === '' || status === filterStatusValue;

// Mostrar ou esconder a linha
row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
});

// Mostra a mensagem de 'Nenhum paciente encontrado' se todas as linhas estiverem ocultas
checkNoResults();

}

// Lógica para o filtro de Status (Dropdown customizado)
const customSelect = document.getElementById("customStatus");
const selectedInput = customSelect.querySelector(".selected-filter-input");
const options = customSelect.querySelector(".options");
const hiddenStatusInput = document.getElementById('filterStatus');

selectedInput.addEventListener("click", () => {
// Alterna a classe 'active' para controlar a visibilidade e o estilo do dropdown
customSelect.classList.toggle('active');
});

options.querySelectorAll("div").forEach(option => {
option.addEventListener("click", () => {
const selectedValue = option.getAttribute('data-value');
const selectedText = option.textContent;

// 1. Atualiza o texto do input
selectedInput.value = selectedText;

// 2. Atualiza o valor do filtro oculto
hiddenStatusInput.value = selectedValue;

// 3. Fecha o dropdown
customSelect.classList.remove('active');

// 4. Aplica os filtros
// A correção principal aqui é garantir que a função de filtro seja chamada corretamente
filterPatients();
});

});

// Fecha o dropdown quando clicar fora
document.addEventListener("click", e => {
if (!customSelect.contains(e.target)) {
customSelect.classList.remove('active');
}
});

// Função para mostrar a mensagem "Nenhum paciente encontrado"
function checkNoResults() {
const rows = document.querySelectorAll('tbody tr');
let visibleCount = 0;
rows.forEach(row => {
// Contabiliza apenas linhas de paciente (que possuem data-name) e que estão visíveis
if (row.dataset.name && row.style.display !== 'none') {
visibleCount++;
}
});

const noPatientsRow = document.querySelector('td.no-enfermeiros')?.closest('tr');
// Se a linha 'Nenhum paciente encontrado' existir...
if (noPatientsRow) {
// Se o contador for 0, mostra a linha. Caso contrário, esconde.
noPatientsRow.style.display = visibleCount === 0 ? '' : 'none';
}

}

// Inicializa a verificação ao carregar (importante para o caso de a lista de pacientes vir vazia)
window.onload = checkNoResults;
</script>

@endsection