<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Consulta;
use Carbon\Carbon;

class MedicoPdfController extends Controller
{ 
    private function getMedicoData($consulta)
    {
        if ($consulta->medico) {
            return $consulta->medico;
        }

        return (object) [
            'nomeMedico' => $consulta->nomeMedico ?? 'N/A',
            'crmMedico' => $consulta->crmMedico ?? 'N/A',
            'especialidadeMedico' => $consulta->medico->especialidadeMedico ?? 'N/A',
        ];
    }

    public function gerarPdfExames($idConsulta)
    {
        try {
            $consulta = Consulta::with(['paciente', 'medico', 'prontuario'])
                ->where('idConsultaPK', $idConsulta)
                ->firstOrFail();

            $medicoInfo = $this->getMedicoData($consulta);

            $data = [
                'paciente' => $consulta->paciente,
                'medico' => $medicoInfo,
                'consulta' => $consulta,
                'exames' => $consulta->examesSolicitados ?? 'Nenhum exame solicitado.',
                'numProntuario' => $consulta->prontuario 
                    ? str_pad($consulta->prontuario->idProntuarioPK, 6, '0', STR_PAD_LEFT) 
                    : 'N/A',
                'dataEmissao' => Carbon::now()->format('d/m/Y'),
            ];

            $pdf = PDF::loadView('medico.pdf.pedidoExames', $data);

            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'chroot' => public_path(),
                'defaultPaperSize' => 'a4',
                'defaultOrientation' => 'portrait',
                'marginBottom' => 5,
                'marginLeft' => 10,
                'marginRight' => 10,
                'marginTop' => 10,
                'dpi' => 150,
            ]);

            $nomeArquivo = 'Solicitacao_Exames_' . 
                preg_replace('/[^a-zA-Z0-9]/', '_', $consulta->paciente->nomePaciente) . '_' . 
                Carbon::parse($consulta->dataConsulta)->format('Ymd') . '.pdf';

            return $pdf->download($nomeArquivo);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF de Exames: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar PDF de Exames: ' . $e->getMessage());
        }
    }

    public function gerarPdfReceita($idConsulta)
    {
        try {
            $consulta = Consulta::with(['paciente', 'medico', 'prontuario'])
                ->where('idConsultaPK', $idConsulta)
                ->firstOrFail();

            $medicoInfo = $this->getMedicoData($consulta);

            $data = [
                'paciente' => $consulta->paciente,
                'medico' => $medicoInfo,
                'consulta' => $consulta,
                'medicamentos' => $consulta->medicamentosPrescritos ?? 'Nenhum medicamento prescrito.',
                'numProntuario' => $consulta->prontuario 
                    ? str_pad($consulta->prontuario->idProntuarioPK, 6, '0', STR_PAD_LEFT) 
                    : 'N/A',
                'dataEmissao' => Carbon::now()->format('d/m/Y'),
            ];

            $pdf = PDF::loadView('medico.pdf.receitaMedica', $data);

            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'chroot' => public_path(),
                'defaultPaperSize' => 'a4',
                'defaultOrientation' => 'portrait',
                'marginBottom' => 5,
                'marginLeft' => 10,
                'marginRight' => 10,
                'marginTop' => 10,
                'dpi' => 150,
            ]);

            $nomeArquivo = 'Receita_Medica_' . 
                preg_replace('/[^a-zA-Z0-9]/', '_', $consulta->paciente->nomePaciente) . '_' . 
                Carbon::parse($consulta->dataConsulta)->format('Ymd') . '.pdf';

            return $pdf->download($nomeArquivo);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF da Receita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar PDF da Receita: ' . $e->getMessage());
        }
    }
}