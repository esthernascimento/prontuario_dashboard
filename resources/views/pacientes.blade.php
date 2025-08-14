<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pacientes - Prontuário+</title>
 
  <link rel="stylesheet" href="{{url('/css/dashboard.css')}}">
  <link rel="stylesheet" href="{{url('/css/pacientes.css')}}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="sidebar">
    <img src="{{asset('img/logo-branco.png')}}" class="logo">
  <nav>
    <a href="{{url('/dashboard')}}" title="Dashboard"><i class="bi bi-house-door-fill"></i></a>
    <a href="{{url('/pacientes')}}" title="Pacientes"><i class="bi bi-people-fill"></i></a>
    <a href="{{url('/ajuda')}}" title="Ajuda"><i class="bi bi-question-circle-fill"></i></a>
    <a href="{{url('/seguranca')}}" title="Segurança"><i class="bi bi-shield-lock-fill"></i></a>
    <a href="{{url('/logout')}}" title="Sair"><i class="bi bi-power"></i></a>
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