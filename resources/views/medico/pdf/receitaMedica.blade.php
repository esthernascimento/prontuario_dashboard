<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Receita Médica</title>
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
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
    }

    .header {
      text-align: center;
      border-bottom: 3px solid #e11d48;
      padding-bottom: 15px;
      margin-bottom: 25px;
    }

    .header h1 {
      color: #b91c1c;
      font-size: 30px;
      font-weight: 800;
      text-transform: uppercase;
      margin: 0;
      letter-spacing: 1px;
    }

    .header p {
      font-weight: 600;
      font-size: 18px;
      color: #374151;
      margin-top: 6px;
    }

    .info-block,
    .clinical-reason.no-break,
    .exames-section {
      background: #fff;
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 22px 24px;
      margin-top: 40px;
      transition: all 0.25s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .info-block:hover,
    .clinical-reason:hover,
    .exames-section:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
      border-color: #f87171;
      background: #fff;
    }

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

    .clinical-reason {
      background: #f9fafb;
    }

    .clinical-reason h3 {
      color: #1f2937;
    }

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

    .exames-content {
      font-size: 18px;
      line-height: 1.6;
      color: #111827;
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
      font-size: 16px;
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

    .date-section {
      text-align: right;
      margin-top: 35px;
      font-weight: 600;
      font-size: 18px;
      color: #374151;
    }

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

    .footer {
      text-align: center;
      font-size: 18px;
      color: #6b7280;
      border-top: 1px dashed #d1d5db;
      margin-top: 40px;
      padding-top: 15px;
      font-style: italic;
    }

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
      <h1>RECEITA MÉDICA</h1>
      <p>{{ $consulta->unidade ?? 'Unidade de Saúde' }}</p>
    </div>

    <div class="info-block no-break">
      <h2>Dados do Paciente</h2>
      <div class="patient-info">
        <div>
          <span class="info-label">Nome: {{ $paciente->nomePaciente }}</span>
        </div>
        <div>
          <span class="info-label">CPF: {{ $paciente->cpfPaciente }}</span>
        </div>
        <div>
          <span class="info-label">Nascimento: {{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') }}</span>
        </div>
      </div>
    </div>

    @if($consulta->queixa_principal)
    <div class="clinical-reason no-break">
      <h3>Queixa Principal / Motivo da Consulta</h3>
      <p>{{ $consulta->queixa_principal }}</p>
    </div>
    @endif

    <div class="exames-section no-break">
      <h2>Prescrição Médica</h2>
      <div class="exames-content">
        @if(isset($medicamentosArray) && count($medicamentosArray) > 0)
            <ul class="exames-list">
                @foreach($medicamentosArray as $medicamento)
                    <li>{{ $medicamento }}</li>
                @endforeach
            </ul>
        @else
            <p>Nenhum medicamento prescrito.</p>
        @endif
      </div>
    </div>

    <div class="date-section no-break">
      Data: {{ $dataEmissao }}
    </div>

    <div class="signature no-break">
      <div class="signature-line"></div>
      <div class="medico-info">
        Dr(a). {{ $medico->nomeMedico }}<br>
        CRM: {{ $medico->crmMedico }}
      </div>
    </div>

    <div class="footer">
      Documento gerado eletronicamente pelo Sistema Médico.<br>
      Válido sem assinatura física de acordo com a legislação vigente.
    </div>
  </div>
</body>
</html>