<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prontuário+</title>
  
  <link rel="stylesheet" href="{{ asset('css/geral/index.css') }}">
  
</head>

<body>
    <main class="main-container">

        <div class="box-content">
            <div class="content">
                <h1>Seja Bem-Vindo ao Prontuário+</h1>
                <a href="{{ url('/loginAdm') }}"><button  >SOU <p>ADMINISTRADOR</p></button></a>

                <a href="{{ url('/loginEnfermeiro') }}"><button  >SOU <p>ENFERMEIRO</p></button></a>

                <a href="{{ url('/loginMedico') }}"><button  >SOU <p>MÉDICO</p></button></a>

            </div>

            <div class="img-content">
                <img src="{{asset('img/medico-logo1.png')}}" class="logo">
            </div>

        </div>

        

       

    </main>
</body>

</html>