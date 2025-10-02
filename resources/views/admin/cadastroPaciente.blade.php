<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+</title>
  
  <link rel="stylesheet" href="{{ asset('css/admin/cadastroPaciente.css') }}">
  
</head>
  
<body>
  <main class="main-container">

    <!-- Lado azul com a logo -->
    <div class="logo-area">
      <img src="{{ asset('img/adm-logo1.png') }}" alt="Logo Prontuário" />
    </div>

    <!-- Card de cadastro -->
    <div class="cads-area">
    <form action="{{ route('admin.paciente.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="data_nasc">Data de Nascimento:</label>
            <input type="date" name="data_nasc" id="data_nasc" class="form-control">
        </div>

        <div class="form-group">
            <label for="cartao_sus">Cartão SUS:</label>
            <input type="text" name="cartao_sus" id="cartao_sus" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="caminho_foto">Foto (opcional):</label>
            <input type="file" name="caminho_foto" id="caminho_foto" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Paciente</button>
    </form>
    </div>

  </main>
</body>
</html>
