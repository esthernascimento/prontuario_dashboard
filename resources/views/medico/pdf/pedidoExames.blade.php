<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pedido de Exames - {{ $paciente->nomePaciente }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 12px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #DC2626;
            padding-bottom: 15px;
            margin-bottom: 25px;
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
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
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
        .exames-section {
            border: 2px solid #DC2626;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            background-color: #fef2f2;
            min-height: 200px;
        }
        .exames-section h2 {
            text-align: center;
            color: #DC2626;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .exames-content {
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            min-height: 150px;
        }
        .signature {
            margin-top: 60px;
            text-align: center;
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
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            color: #666;
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
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <h1>PEDIDO DE EXAMES MÉDICOS</h1>
            <p>Emissão: {{ $dataEmissao }} | Prontuário Nº: {{ $numProntuario }}</p>
        </div>

        <div class="info-block">
            <h2>Dados do Paciente</h2>
            <div class="patient-info">
                <div>
                    <span class="info-label">Nome:</span> {{ $paciente->nomePaciente }}
                </div>
                <div>
                    <span class="info-label">CPF:</span> {{ $paciente->cpfPaciente }}
                </div>
                <div>
                    <span class="info-label">Data de Nascimento:</span> 
                    {{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->format('d/m/Y') }}
                </div>
                <div>
                    <span class="info-label">Idade:</span> 
                    {{ \Carbon\Carbon::parse($paciente->dataNascPaciente)->age }} anos
                </div>
            </div>
        </div>

        <div class="info-block">
            <h2>Dados do Atendimento</h2>
            <div class="patient-info">
                <div>
                    <span class="info-label">Data da Consulta:</span> 
                    {{ \Carbon\Carbon::parse($consulta->dataConsulta)->format('d/m/Y') }}
                </div>
                <div>
                    <span class="info-label">Médico:</span> 
                    Dr(a). {{ $medico->nomeMedico }}
                </div>
                <div>
                    <span class="info-label">CRM:</span> 
                    {{ $medico->crmMedico }}
                </div>
                @if($consulta->unidade)
                <div>
                    <span class="info-label">Unidade:</span> 
                    {{ $consulta->unidade }}
                </div>
                @endif
            </div>
        </div>

        <div class="exames-section">
            <h2>EXAMES SOLICITADOS</h2>
            <div class="exames-content">
                {{ $exames ?? 'Nenhum exame solicitado' }}
            </div>
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <div class="medico-info">
                Dr(a). {{ $medico->nomeMedico }}<br>
                CRM: {{ $medico->crmMedico }}<br>
                {{ \Carbon\Carbon::parse($consulta->dataConsulta)->format('d/m/Y') }}
            </div>
        </div>

        <div class="footer">
            Documento gerado eletronicamente pelo Sistema Médico.<br>
            Válido sem assinatura física de acordo com a legislação vigente.
        </div>
    </div>
</body>
</html>