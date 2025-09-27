@extends('admin.templates.admTemplate')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/excluirMedico.css') }}">

    @php $admin = auth()->guard('admin')->user(); @endphp

    <main class="main-dashboard">
        <div class="cadastrar-container">
            <div class="cadastrar-header">
                <i class="bi bi-trash-fill"></i>
                <h1>Excluir Médico</h1>
            </div>

            <p>Tem certeza que deseja excluir o médico <b>{{ $medico->nomeMedico }}</b>?</p>

            {{-- Novo container para agrupar e centralizar os botões --}}
            <div class="button-group"> 
                <form action="{{ route('admin.medicos.excluir', $medico->idMedicoPK) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn-excluir">
                        Sim, excluir
                    </button>
                </form>

                <a href="{{ route('admin.manutencaoMedicos') }}" class="btn-cancelar">
                    Cancelar
                </a>
            </div>

        </div>
    </main>

@endsection