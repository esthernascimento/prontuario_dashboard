<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acesso ao sistema Prontuário+</title>
    <style type="text/css">
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            color: #1a1a1a;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 640px;
            width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(80, 0, 120, 0.25);
        }

        .header {
            background: linear-gradient(135deg, #3b006e 0%, #a855f7 100%);
            padding: 48px 40px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .logo-container {
            width: 200px;
            height: 120px;
            margin: 0 auto 24px;
            background-image: url('https://via.placeholder.com/200x120?text=Prontuario%2B');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            z-index: 1;
            position: relative;
        }

        .header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 16px;
            margin-top: 12px;
            z-index: 1;
            position: relative;
        }

        .content {
            padding: 48px 40px;
        }

        .welcome-text {
            text-align: center;
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .credential-card {
            background: linear-gradient(135deg, #f7f7fb 0%, #ececff 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #dcdcdc;
        }

        .credential-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #7c3aed;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .credential-value {
            background: #fff;
            border-radius: 8px;
            padding: 16px 20px;
            font-size: 18px;
            font-weight: 700;
            color: #4c1d95;
            text-align: center;
            border: 2px solid #e5e7eb;
            word-break: break-all;
        }

        .info-banner {
            background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%);
            border-left: 4px solid #7c3aed;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 32px 0;
        }

        .info-banner p {
            margin: 0;
            color: #4c1d95;
            font-size: 14px;
            line-height: 1.5;
        }

        .footer-section {
            background: #f9f9fb;
            padding: 32px 40px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer-section p {
            font-size: 14px;
            color: #7c7c7c;
            margin-bottom: 12px;
        }

        .footer-section .signature {
            font-weight: 600;
            color: #6d28d9;
            margin-top: 20px;
        }

        .footer-section .auto-message {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #dcdcdc;
        }

        @media only screen and (max-width: 480px) {
            .content { padding: 24px 16px !important; }
            .credential-card { padding: 16px !important; }
            .credential-value { font-size: 16px !important; }
            .header { padding: 32px 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f5f3ff; min-height: 100vh;">
        <tr>
            <td align="center" valign="top" style="padding: 40px 20px;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="container">
                    <tr>
                        <td class="header">
                            <div class="logo-container"></div>
                            <h1>Bem-vindo ao Prontuário+</h1>
                            <p>Seu acesso foi criado com sucesso!</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p class="welcome-text">
                                Olá, <strong>{{ $unidade->nomeUnidade }}</strong>! 👋<br><br>
                                Seu cadastro no sistema <strong>Prontuário+</strong> foi realizado com sucesso.
                            </p>

                            <div class="credential-card">
                                <div class="credential-label">🔑 Senha temporária</div>
                                <div class="credential-value">{{ $senhaTemporaria }}</div>
                            </div>

                            <div class="info-banner">
                                <p><strong>Atenção:</strong> Recomendamos que você altere essa senha após o primeiro login.</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer-section">
                            <p>Atenciosamente,</p>
                            <p class="signature">Equipe Prontuário+</p>
                            <p class="auto-message">Este é um e-mail automático. Por favor, não responda.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
