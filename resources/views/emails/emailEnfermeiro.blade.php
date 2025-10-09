<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acesso de Enfermeiro — Prontuário+</title>
    <style type="text/css">
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            color: #1a1a1a;
            background-color: #ffffff;
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
            box-shadow: 0 20px 60px rgba(10, 64, 12, 0.3);
        }

        .header {
            background: linear-gradient(135deg, #0a400c 0%, #53b15f 100%);
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

        /* --- OTIMIZAÇÕES PARA O LOGO NO HEADER --- */
        .logo-container {
            width: 200px; /* Adapta-se ao tamanho do logo */
            height: 120px; /* Adapta-se ao tamanho do logo */
            margin: 0 auto 24px;
            background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAaVBMVEUAAAAAAAAAAAAAAACgoKAgICAAAACOjo6Dg4MAAAD///+qqqra2trW1tbt7e3y8vLu7u7FxcU2NjY+Pj5BQUHj4+PNzc3CwsLT09OEhIShoaGnp6eSkpI7Ozs+Pj4bGxvR0dGvr69ubm7vWj8EAAAACXBIWXMAAAsTAAALEwEAjssnAAACfklEQVR4nO3c61LCUBRG4U/tI4iKgKACjL3/W+yXw1U6dUpSjKkK+aYxczQzM+OEEgAAAAAAAAAAAAAAAADAa+N93R3f3t3F3x33r/j23j3d7m6q756R14Xv13d3YXeL7/t3cffzR/O91x2f+s3v809E3+k52L/d1X3s7+5u0H/T3x37p/9W92e/D9y/v7m7yP/dvdndLbjfB/+7vPv78/7n213E38fvd3d3eD+/d7v/Wf3pP5r88/1L35+L/r53d3d3z/P5/mY9d8ePz98v7++vT8ff2d09/n2/Vl7n15/63P3a8Pnt5f0Vf5+3+8/6+P5n+6d9fGf1f2v7/P2/5/v/d3Xvd3eL72/uXu/6+c+v/n54X7x/qf954fO/a+P7m4v05n79+eL94e33S//R5c/+f2f2/c3d3D/9v76/3F/d3fHf/d3d3d0tvr/f7fD7n5Lz7+f79e3h+f3H1f2d+/u/xO/V+V5/6zO7y7sL/N3n7h58/2vQf9Pd//v3R73v7v+Ld8d322/nO+j/s7u7u7tJ+7n3vLu9u8P//d3h+f3P3/9j33t3t/z/d7v/d3d1d2s+dnd3Xh98X93eXd/d4f//f0//D3T3f+b27u7vT++3d3Xf7+f768u7u7vTu7u7u7v5f/e3u7u7u/u9+v7s/u7tJ+7n3f3d3i+//v/v7v7u7u7vT+7u7u7u7u/u/fHf393f3d3f4/f7u7u/u7u7u7tL+v/v7u7u7u7u7w/v93d3d3V3d3d1d2s8fAABQ8f29AAAAfL29AAAA/F5fB+8AAAAA4fF1fgAAAAAAAP8B/Qf/NncAAAAASUVORK5CYII');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
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

        .welcome-text {
            text-align: center;
            font-size: 16px;
            color: #4a5568;
            line-height: 1.6;
            margin: 0 0 40px 0;
        }

        .welcome-text strong {
            color: #0a400c;
            font-weight: 600;
        }

        .credential-card {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid #dcdcdc;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .credential-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(10, 64, 12, 0.15);
        }

        .credential-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #718096;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .credential-label::before {
            content: '';
            width: 4px;
            height: 4px;
            background: #53b15f;
            border-radius: 50%;
        }

        .credential-value {
            background: #ffffff;
            border-radius: 8px;
            padding: 16px 20px;
            font-size: 17px;
            font-weight: 600;
            color: #0a400c;
            text-align: center;
            border: 2px solid #dcdcdc;
            word-break: break-all;
        }

        .credential-value.email {
            color: #2d3748;
            font-weight: 500;
        }

        .credential-value.password {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            font-size: 20px;
            color: #0a400c;
            font-weight: 700;
        }

        .info-banner {
            background: linear-gradient(135deg, #fef5e7 0%, #fdeaa7 100%);
            border-left: 4px solid #f39c12;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 32px 0;
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .info-banner::before {
            content: '🔒';
            font-size: 20px;
            flex-shrink: 0;
        }

        .info-banner p {
            margin: 0;
            color: #7d6608;
            font-size: 14px;
            line-height: 1.5;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0a400c 0%, #53b15f 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 24px 0;
            box-shadow: 0 8px 20px rgba(10, 64, 12, 0.15);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(10, 64, 12, 0.25);
        }

        .footer-section {
            background: #f7fafc;
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
            color: #0a400c;
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
            .logo-container {
                width: 140px !important;
                height: 90px !important;
                padding: 15px !important;
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
            .welcome-text {
                font-size: 14px !important;
                margin-bottom: 28px !important;
            }
            .credential-card {
                padding: 16px !important;
                margin-bottom: 16px !important;
            }
            .credential-label {
                font-size: 11px !important;
            }
            .credential-value {
                font-size: 15px !important;
                padding: 14px 16px !important;
            }
            .credential-value.password {
                font-size: 18px !important;
                letter-spacing: 1px !important;
            }
            .info-banner {
                padding: 14px 16px !important;
                margin: 24px 0 !important;
            }
            .info-banner p {
                font-size: 13px !important;
            }
            .cta-button {
                width: 100% !important;
                padding: 14px 20px !important;
                font-size: 15px !important;
                box-sizing: border-box;
            }
            .footer-section {
                padding: 24px 16px !important;
            }
            .footer-section p {
                font-size: 13px !important;
            }
        }

        /* Extra small devices */
        @media only screen and (max-width: 360px) {
            .header {
                padding: 24px 15px !important;
            }
            .logo-container {
                width: 120px !important;
                height: 80px !important;
            }
            .header h1 {
                font-size: 20px !important;
            }
            .content {
                padding: 20px 12px !important;
            }
            .credential-value.password {
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; min-height: 100vh;">
        <tr>
            <td align="center" valign="top" style="padding: 40px 20px;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="container" style="max-width: 640px;">
                    <tr>
                        <td class="header">
                            <div class="logo-container"></div>
                            <h1>Bem-vindo ao Prontuário+</h1>
                            <p>Sua jornada na saúde digital começa aqui</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p class="welcome-text">
                                Olá, <strong>{{ $usuario->nomeUsuario }}</strong>! 👋<br><br>
                                Você foi cadastrado como <strong>enfermeiro(a)</strong> no sistema Prontuário+. 
                                Estamos felizes em tê-lo(a) conosco!
                            </p>
                            
                            <div class="credential-card">
                                <div class="credential-label">📧 Seu e-mail de acesso</div>
                                <div class="credential-value email">{{ $usuario->emailUsuario }}</div>
                            </div>

                            <div class="credential-card">
                                <div class="credential-label">🔑 Senha temporária</div>
                                <div class="credential-value password">{{ $senhaTemporaria }}</div>
                            </div>

                            <div class="info-banner">
                                <p><strong>Importante:</strong> Por segurança, você deverá alterar essa senha no seu primeiro login. Mantenha suas credenciais em local seguro.</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer-section">
                            <p>Se você tiver alguma dúvida ou precisar de suporte, nossa equipe está pronta para ajudar.</p>
                            <p class="signature">
                                Atenciosamente,<br>
                                <strong>Equipe Prontuário+</strong>
                            </p>
                            <p class="auto-message">
                                Este é um e-mail automático. Por favor, não responda.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>