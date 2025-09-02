<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Pacientes - Prontuário+</title>

  <link rel="stylesheet" href="{{url('/css/admin/dashboardAdm.css')}}">
  <link rel="stylesheet" href="{{url('/css/geral/pacientes.css')}}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

  <div class="sidebar">
    <img src="{{asset('img/adm-logo2.png')}}" class="logo">
    <nav>
      <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i></a>
      <a href="{{ route('admin.pacientes') }}"><i class="bi bi-people-fill"></i></a>
      <a href="{{ route('admin.manutencaoMedicos') }}"><i class="bi bi-plus-circle-fill"></i></a>
      <a href="{{ route('admin.ajuda') }}"><i class="bi bi-question-circle-fill"></i></a>
      <a href="{{ route('admin.seguranca') }}"><i class="bi bi-shield-lock-fill"></i></a>
      <a href="{{ route('admin.logout') }}"><i class="bi bi-power"></i></a>
    </nav>
  </div>

  <div class="main-dashboard-wrapper">
    <header class="header">
      <div class="user-info">
        <img src="{{ asset('img/julia.png')}}" alt="Dra. Júlia">
        <span>Dra. Júlia Marcelli</span>
      </div>
    </header>

    <main class="main-dashboard">
      <div class="patients-container">
        <div class="patients-header">
          <h1><i class="bi bi-people-fill"></i> Gerenciamento de Pacientes</h1>
        </div>

        <!-- Barra de pesquisa e filtros -->
        <div class="search-filters">
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF ou telefone..."
              onkeyup="filterPatients()">
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