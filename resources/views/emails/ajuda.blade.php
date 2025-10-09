<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova Mensagem de Suporte — Prontuário+</title>
    <style type="text/css">
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            color: #1a1a1a;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        .container {
            max-width: 640px;
            width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(6, 24, 185, 0.3);
        }

        .header {
            background: linear-gradient(135deg, #0618b9 0%, #304bff 100%);
            padding: 48px 40px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            line-height: 1.3;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 16px;
            margin: 12px 0 0 0;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 48px 40px;
        }

        .info-card {
            background: linear-gradient(135deg, #f7f7f7 0%, #ececec 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #dcdcdc;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .info-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #0618b9;
            margin: 0 0 12px 0;
            border-bottom: 2px solid #0618b9;
            padding-bottom: 8px;
            display: inline-block;
        }

        .info-card p {
            font-size: 15px;
            color: #4a5568;
            line-height: 1.6;
            margin: 0 0 10px 0;
        }

        .footer-section {
            background: #f7f7f7;
            padding: 32px 40px;
            text-align: center;
            border-top: 1px solid #dcdcdc;
        }

        .footer-section p {
            font-size: 14px;
            color: #718096;
            line-height: 1.6;
            margin: 0 0 12px 0;
        }

        .footer-section .signature {
            font-weight: 600;
            color: #0618b9;
            margin-top: 20px;
        }

        .footer-section .auto-message {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #dcdcdc;
        }

        /* Mobile adjustments */
        @media only screen and (max-width: 480px) {
            .container {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
            .header {
                padding: 32px 20px !important;
            }
            .header h1 {
                font-size: 22px !important;
            }
            .header p {
                font-size: 14px !important;
            }
            .content {
                padding: 24px 16px !important;
            }
            .info-card {
                padding: 16px !important;
            }
            .info-card p {
                font-size: 14px !important;
            }
            .footer-section {
                padding: 24px 16px !important;
            }
            .footer-section p {
                font-size: 13px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7f7f7; min-height: 100vh;">
        <tr>
            <td align="center" valign="top" style="padding: 40px 20px;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="container" style="max-width: 640px;">
                    <tr>
                        <td class="header">
                            <h1>Nova Mensagem de Suporte</h1>
                            <p>Uma nova dúvida foi enviada através da Central de Ajuda.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <div class="info-card">
                                <h3>Informações do Contato</h3>
                                <p><strong>Nome:</strong> {{ $nomeUsuario }}</p>
                                <p><strong>E-mail:</strong> {{ $emailUsuario }}</p>
                            </div>
                            
                            <div class="info-card" style="margin-top: 20px;">
                                <h3>Detalhes da Mensagem</h3>
                                <p><strong>Assunto:</strong> {{ $assunto }}</p>
                                <p><strong>Mensagem:</strong></p>
                                <p style="white-space: pre-wrap;">{{ $mensagem }}</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer-section">
                            <p>Esta mensagem foi gerada automaticamente pelo sistema.</p>
                            <p class="signature">
                                Atenciosamente,<br>
                                <strong>Equipe Prontuário+</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>