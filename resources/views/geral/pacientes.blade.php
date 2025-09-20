@extends('geral.templates.geralTemplate') 

@section('content') 

@php $admin = auth()->guard('admin')->user(); 

@endphp 

<main class="main-dashboard"> 
  <div class="patients-container"> 
    <div class="patients-header"> 
      <h1><i class="bi bi-people-fill">

      </i> Gerenciamento de Pacientes</h1> </div> 
      <!-- Barra de pesquisa e filtros --> 
      <div class="search-filters"> 
        <div class="search-box"> 
          <i class="bi bi-search"></i> 
          <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF ou telefone..." onkeyup="filterPatients()"> 
        </div> 
        <div class="filters">

          <select id="filterAge" onchange="filterPatients()"> 
            <option value="">Todas as idades</option> 
            <option value="crianca">Crianças (0-12)</option> 
            <option value="adolescente">Adolescentes (13-17)</option> 
            <option value="adulto">Adultos (18-59)</option> 
            <option value="idoso">Idosos (60+)</option> 
          </select> 

          <select id="filterGender" onchange="filterPatients()"> 
            <option value="">Todos os gêneros</option> 
            <option value="M">Masculino</option> 
            <option value="F">Feminino</option> 
          </select>

        </div> 
      </div>
      
@endsection