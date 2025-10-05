@extends('admin.templates.admTemplate')

@section('content')
    @php
        $admin = auth()->guard('admin')->user();
        use App\Models\Paciente;
        $pacientes = $pacientes ?? Paciente::orderBy('nomePaciente')->paginate(10);
    @endphp

    <link rel="stylesheet" href="{{ asset('css/admin/pacientes.css') }}">

    <main class="main-dashboard">
        <div class="patients-container">
            <div class="patients-header">
                <h1><i class="bi bi-people-fill"></i> Gerenciamento de Pacientes</h1>

                {{-- BOTÃO DE CADASTRAR PACIENTE --}}
                <a href="{{ url('/cadastroPaciente') }}" class="btn-add-paciente">
                    <i class="bi bi-plus-circle"></i> Cadastrar Paciente
                </a>
            </div>

            <div class="search-filters">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Pesquisar por nome, CPF ou cartão SUS..."
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

                <div class="custom-select" id="customStatus">
                    <div class="selected">Todos os Status</div>
                    <div class="options">
                        <div data-value="">Todos os Status</div>
                        <div data-value="ativo">Ativo</div>
                        <div data-value="inativo">Inativo</div>
                    </div>
                </div>
                <input type="hidden" id="filterStatus" value="">

            </div>

            <div class="table-wrapper">
                <table class="patients-table">
                    <thead>
                        <tr>
                            <th>NOME</th>
                            <th>CPF</th>
                            <th>IDADE</th>
                            <th>CARTÃO SUS</th>
                            <th>STATUS</th>
                            <th>AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pacientes as $paciente)
                            @php
                                $idade = \Carbon\Carbon::parse($paciente->dataNascPaciente)->age;
                                $statusDisplay = ucfirst($paciente->statusPaciente);
                                $statusAtivo = strtolower($paciente->statusPaciente) == 'ativo';
                            @endphp
                            <tr data-age-group="{{ $idade <= 12 ? 'crianca' : ($idade <= 17 ? 'adolescente' : ($idade <= 59 ? 'adulto' : 'idoso')) }}"
                                data-gender="{{ strtoupper(substr($paciente->generoPaciente ?? '', 0, 1)) }}"
                                data-status="{{ strtolower($paciente->statusPaciente) }}">
                                <td>{{ $paciente->nomePaciente }}</td>
                                <td>{{ $paciente->cpfPaciente }}</td>
                                <td>{{ $idade }} anos</td>
                                <td>{{ $paciente->cartaoSusPaciente ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($paciente->statusPaciente) }}">
                                        {{ $statusDisplay }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- LINK DE EDIÇÃO --}}
                                        <a href="{{ url('/paciente/' . $paciente->idPacientePK . '/editar') }}"
                                            class="btn-action btn-edit" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- LINK DE ALTERAR STATUS --}}
                                        <a href="#"
                                            onclick="openStatusPacienteModal('{{ $paciente->idPacientePK }}', '{{ $paciente->nomePaciente }}', '{{ $paciente->statusPaciente }}')"
                                            class="btn-action" title="{{ $statusAtivo ? 'Desativar' : 'Ativar' }}">
                                            @if($statusAtivo)
                                                <i class="bi bi-slash-circle text-danger"></i>
                                            @else
                                                <i class="bi bi-check-circle text-success"></i>
                                            @endif
                                        </a>

                                        {{-- LINK DE EXCLUSÃO --}}
                                        <a href="#"
                                            onclick="openDeletePacienteModal('{{ $paciente->idPacientePK }}', '{{ $paciente->nomePaciente }}')"
                                            class="btn-action btn-delete" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-patients">Nenhum paciente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                {{ $pacientes->links() }}
            </div>

        </div>

    </main>

    {{-- MODAL EXCLUSÃO --}}
    <div id="deletePacienteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="bi bi-trash-fill"></i>
                <h2>Excluir Paciente</h2>
            </div>
            <p>Tem certeza que deseja excluir o paciente <span id="pacienteNome"></span>?</p>

            <form id="deletePacienteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-buttons">
                    <button type="button" onclick="closeDeletePacienteModal()" class="btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn-excluir">Sim, excluir</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ALTERAR STATUS --}}
    <div id="statusPacienteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="bi bi-toggle-on"></i>
                <h2>Alterar Status</h2>
            </div>
            <p>Tem certeza que deseja <span id="statusAction"></span> o(a) paciente <span id="statusPacienteNome"></span>?</p>

            <form id="statusPacienteForm" method="POST">
                @csrf
                <div class="modal-buttons">
                    <button type="button" onclick="closeStatusPacienteModal()" class="btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn-excluir">Sim, <span id="confirmStatusText"></span></button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DE SUCESSO --}}
    <div id="statusSuccessModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="bi bi-check-circle-fill"></i>
                <h2>Sucesso!</h2>
            </div>
            <p id="successMessage"></p>
            <div class="modal-buttons">
                <button type="button" onclick="closeSuccessModal()" class="btn-excluir">Fechar</button>
            </div>
        </div>
    </div>

    <script>
        function openDeletePacienteModal(id, nome) {
            document.getElementById('pacienteNome').textContent = nome;
            const form = document.getElementById('deletePacienteForm');
            form.action = "{{ url('/paciente') }}/" + id; // rota direta para exclusão
            document.getElementById('deletePacienteModal').style.display = 'flex';
        }

        function closeDeletePacienteModal() {
            document.getElementById('deletePacienteModal').style.display = 'none';
        }

        function openStatusPacienteModal(id, nome, statusAtual) {
            const modal = document.getElementById('statusPacienteModal');
            document.getElementById('statusPacienteNome').textContent = nome;
            const ativo = statusAtual.toLowerCase() === 'ativo';
            document.getElementById('statusAction').textContent = ativo ? 'desativar' : 'ativar';
            document.getElementById('confirmStatusText').textContent = ativo ? 'desativar' : 'ativar';
            const form = document.getElementById('statusPacienteForm');
            form.action = "{{ url('/paciente') }}/" + id + "/toggle-status";
            modal.style.display = 'flex';
        }

        function closeStatusPacienteModal() {
            document.getElementById('statusPacienteModal').style.display = 'none';
        }

        function openSuccessModal(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('statusSuccessModal').style.display = 'flex';
        }

        function closeSuccessModal() {
            document.getElementById('statusSuccessModal').style.display = 'none';
            window.location.reload();
        }
    </script>
@endsection
