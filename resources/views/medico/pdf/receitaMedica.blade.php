<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Receita Médica</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #DC2626;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #DC2626;
            font-size: 24px;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-block {
            margin-bottom: 15px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
            page-break-inside: avoid;
        }

        .info-block h2 {
            font-size: 16px;
            color: #111827;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-top: 0;
            font-weight: bold;
        }

        .info-data p {
            margin: 8px 0;
            line-height: 1.4;
        }

        .patient-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .clinical-reason {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
            page-break-inside: avoid;
        }

        .clinical-reason h3 {
            margin-top: 0;
            color: #333;
            font-size: 16px;
        }

        /* SEÇÃO PRINCIPAL DA RECEITA */
        .exames-section {
            border: 2px solid #DC2626;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            background-color: #fef2f2;
            page-break-inside: avoid;
        }

        .exames-section h2 {
            text-align: center;
            color: #DC2626;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .exames-content {
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        /* Caso os medicamentos sejam uma lista, use estes estilos */
        .exames-list {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        .exames-list li {
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .exames-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #DC2626;
            font-weight: bold;
        }

        .date-section {
            text-align: right;
            margin: 15px 0;
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
            page-break-inside: avoid;
        }

        .signature-line {
            height: 1px;
            background-color: #333;
            width: 60%;
            margin: 0 auto 10px auto;
        }

        .medico-info {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            color: #666;
        }

        /* Evitar quebras de página indesejadas */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>RECEITA MÉDICA</h1>
            <p>{{ $consulta->unidade ?? 'Unidade de Saúde' }}</p>
        </div>

        <div class="info-block">
            <h2>Dados do Paciente</h2>
        </div>

        @if($consulta->queixa_principal)
        <div class="clinical-reason">
            <h3>Queixa Principal / Motivo da Consulta</h3>
            <p>{{ $consulta->queixa_principal }}</p>
        </div>
        @endif

        <div class="exames-section">
            <h2>PRESCRIÇÃO MÉDICA</h2>
            <div class="exames-content">

                <p>{!! nl2br(e($medicamentos)) !!}</p>
            </div>
        </div>

        <div class="date-section">
            {{ $dataEmissao }}
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <div class="medico-info">
                <p>{{ $medico->nomeMedico }}</p>
                <p>CRM: {{ $medico->crmMedico }}</p>
            </div>
        </div>

        <div class="footer">
            <p>Documento válido apenas com assinatura e carimbo do médico.</p>
        </div>
    </div>

</body>

</html>