@extends('admin.templates.admTemplate')

  <main class="main-container">

    <!-- Lado azul com a logo -->
    <div class="logo-area">
      <img src="{{ asset('img/enfermeiro-logo1.png') }}" alt="Logo Prontuário" />
    </div>

    <!-- Card de cadastro -->
    <div class="cads-area">
      <form class="cads-card" method="POST" action="/cadastro">
    @csrf

        <h2>Enfermeiro(a) Cadastro</h2>

        <label for="name">Nome completo</label>
        <input type="text" id="name" name="name" required />

        <label for="corem">COREM</label>
        <input type="text" id="corem" name="corem" required />

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required />

        <button class="button" type="submit">CADASTRAR</button>
        <!--<a href="{{url('/loginEnfermeiro')}}">Já tem cadastro? <strong>Entrar</strong></a>!-->

      </form>
    </div>

  </main>
@endsection