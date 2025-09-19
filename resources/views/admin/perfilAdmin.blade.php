@extends('admin.templates.admTemplate')

@section('content')
  <script>
    window.onload = function() {
      @if(session('success'))
      setTimeout(function() {
        window.location.href = "{{ route('admin.dashboard') }}";
      }, 3000);
      @endif

      @if(session('error'))
      setTimeout(function() {
        window.location.href = "{{ route('admin.perfil') }}";
      }, 3000);
      @endif
    };
  </script>
  @php $admin = auth()->guard('admin')->user(); @endphp

  
  <!-- Conteúdo Principal -->
  <main class="main-dashboard">
    <div class="cadastrar-container">
      <div class="cadastrar-header">
        <i class="bi bi-person-circle"></i>
        <h1>Perfil do Administrador</h1>
      </div>

      <form action="{{ route('admin.perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Foto -->
        <div class="foto-upload">
          <label for="foto">
            <i class="bi bi-camera"></i>
            <span>Alterar Foto</span>
          </label>
          <input type="file" id="foto" name="foto" accept="image/*" hidden onchange="previewFoto(event)">
          
          <div class="box-foto">
            <img id="preview-img" src="{{ $admin->foto ? asset('storage/fotos/' . $admin->foto) : asset('img/usuario-de-perfil.png') }}" alt="Foto atual" style="width: 134px; border-radius: 100px; margin-top: 10px;">
          </div>
        </div>

        <!-- Dados -->
        <input type="text" name="nomeAdmin" value="{{ $admin->nomeAdmin }}" required>
        <input type="email" name="emailAdmin" value="{{ $admin->emailAdmin }}" required>

        <button type="submit">Salvar Alterações</button>
      </form>
    </div>
  </main>

  <script>
    function previewFoto(event) {
      const input = event.target;
      const preview = document.getElementById('preview-img');

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endsection