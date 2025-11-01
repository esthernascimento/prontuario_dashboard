<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SOLICITAÇÃO DE EXAMES</title>
<style>
    @page {
        size: A4;
        margin: 0;
    }

    .container {
        max-width: 900px;
        min-height: 100vh;
        margin: 0 auto;
        font-family: 'DejaVu Sans', Arial, sans-serif;
        color: #111827;
        padding: 40px 60px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-sizing: border-box;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        border-radius: 8px;
    }

    /* ===== HEADER ===== */
    .header {
        text-align: center;
        border-bottom: 3px solid #e11d48;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .header h1 {
        color: #b91c1c;
        font-size: 30px;
        letter-spacing: 1px;
        margin: 0;
        font-weight: 800;
        text-transform: uppercase;
    }

    .header p {
        font-weight: 600;
        font-size: 18px;
        color: #374151;
        margin: 6px 0 0 0;
    }

    /* ===== BLOCO PADRÃO ===== */
    .info-block,
    .clinical-reason,
    .exames-section {
        background: #fff;
        border: 1.5px solid #e5e7eb;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 22px 24px;
        margin-top: 50px;
        transition: all 0.25s ease;
    }

    .info-block:hover,
    .clinical-reason:hover,
    .exames-section:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        background: #fff;
        border-color: #f87171;
    }

    /* ===== TÍTULOS DOS BLOCOS ===== */
    .info-block h2,
    .clinical-reason h3,
    .exames-section h2 {
        font-size: 26px;
        font-weight: 700;
        color: #1f2937;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 6px;
        margin-top: 0;
        margin-bottom: 14px;
    }

    /* ===== PACIENTE / MÉDICO ===== */
    .patient-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 10px;
        line-height: 1.6;
    }

    .info-label {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
    }

    /* ===== INDICAÇÃO CLÍNICA ===== */
    .clinical-reason {
        background: #fff;
    }

    .clinical-reason h3 {
        color: #1f2937;
    }

    /* ===== EXAMES ===== */
    .exames-section {
        border: 2px solid #ef4444;
        background: #fff5f5;
        box-shadow: inset 0 0 6px rgba(239, 68, 68, 0.15);
    }

    .exames-section h2 {
        color: #b91c1c;
        text-align: center;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .exames-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .exames-list li {
        position: relative;
        margin-bottom: 10px;
        padding-left: 22px;
        font-size: 15px;
        color: #111827;
    }

    .exames-list li::before {
        content: "•";
        color: #dc2626;
        font-size: 22px;
        position: absolute;
        left: 0;
        top: -2px;
    }

    /* ===== DATA ===== */
    .date-section {
        text-align: right;
        margin-top: 35px;
        font-weight: 600;
        font-size: 18px;
        color: #374151;
    }

    /* ===== ASSINATURA ===== */
    .signature {
        margin-top: 55px;
        text-align: center;
    }

    .signature-line {
        height: 1px;
        background-color: #333;
        width: 60%;
        margin: 0 auto 8px;
    }

    .medico-info {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        line-height: 1.4;
    }

    /* ===== RODAPÉ ===== */
    .footer {
        text-align: center;
        font-size: 18px;
        color: #6b7280;
        border-top: 1px dashed #d1d5db;
        margin-top: 30px;
        padding-top: 35px;
        font-style: italic;
    }

    /* ===== AJUSTES PDF ===== */
    @media print {
        body {
            background: none;
        }
        .container {
            box-shadow: none;
            border-radius: 0;
            padding: 30px 50px;
        }
        .info-block:hover,
        .clinical-reason:hover,
        .exames-section:hover {
            transform: none;
            box-shadow: none;
        }
    }
</style>


</head>
<body>
     <div class="container">
        <div class="header no-break">
            <h1>SOLICITAÇÃO DE EXAMES</h1>
            <p>Data: {{ $dataEmissao ?? 'N/A' }}</p>
        </div>

        <div class="info-block no-break">
            <h2>Dados do Paciente</h2>
            <div class="patient-info">
                <div><span class="info-label">Nome: {{ $paciente->nomePaciente ?? 'N/A' }}</span></div>
                <div><span class="info-label">CPF: {{ $paciente->cpfPaciente ?? 'N/A' }}</span></div>
                <div><span class="info-label">Data de Nascimento: {{ isset($paciente->dataNascPaciente) ? \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') : 'N/A' }}</span></div>
            </div>
        </div>

        <div class="info-block no-break">
            <h2>Dados do Médico Solicitante</h2>
            <div class="patient-info">
                <div><span class="info-label">Nome: Dr(a). {{ $medico->nomeMedico ?? 'N/A' }}</span></div>
                <div><span class="info-label">CRM: {{ $medico->crmMedico ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="clinical-reason no-break">
            <h3>Indicação Clínica</h3>
            <p>{{ $consulta->observacoes ?? 'Avaliação médica geral' }}</p>
        </div>

        <div class="exames-section no-break">
            <h2>EXAMES SOLICITADOS</h2>
            <div class="exames-content">
                @php
                    $examesArray = !empty($exames) && $exames !== 'Nenhum exame solicitado.' 
                        ? array_filter(explode("\n", $exames), fn($e) => trim($e) !== '')
                        : [];
                @endphp
                @if(count($examesArray) > 0)
                    <ul class="exames-list">
                        @foreach($examesArray as $exame)
                            <li>{{ trim($exame) }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>Nenhum exame solicitado.</p>
                @endif
            </div>
        </div>

        <div class="date-section no-break">
            Data: {{ isset($consulta->dataConsulta) ? \Carbon\Carbon::parse($consulta->dataConsulta)->format('d/m/Y') : 'N/A' }}
        </div>

        <div class="signature no-break">
            <div class="signature-line"></div>
            <div class="medico-info">
                Dr(a). {{ $medico->nomeMedico ?? 'N/A' }}<br>
                CRM: {{ $medico->crmMedico ?? 'N/A' }}
            </div>
        </div>

        <div class="footer">
            Documento gerado eletronicamente pelo Sistema Médico.<br>
            Válido sem assinatura física de acordo com a legislação vigente.
        </div>
    </div>
</body>
</html>