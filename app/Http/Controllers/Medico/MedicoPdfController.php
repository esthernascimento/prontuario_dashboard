<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Consulta;

class MedicoPdfController extends Controller
{   
    public function gerarPdfExames($idConsulta)
    {
        try {
            // Busca a consulta com os relacionamentos
            $consulta = Consulta::with(['paciente', 'medico', 'prontuario'])->find($idConsulta);

            if (!$consulta) {
                return redirect()->back()->with('error', 'Consulta não encontrada.');
            }

            // Verifica se existem exames
            if (empty($consulta->examesSolicitados) || trim($consulta->examesSolicitados) === '') {
                return redirect()->back()->with('error', 'Nenhum exame solicitado para esta consulta.');
            }

            // Prepara os dados para o PDF
            $data = [
                'paciente' => $consulta->paciente,
                'medico' => $consulta->medico ?? (object)[
                    'nomeMedico' => $consulta->nomeMedico,
                    'crmMedico' => $consulta->crmMedico
                ],
                'consulta' => $consulta,
                'exames' => $consulta->examesSolicitados,
                'numProntuario' => $consulta->prontuario ? 
                    str_pad($consulta->prontuario->idProntuarioPK, 6, '0', STR_PAD_LEFT) : 'N/A',
                'dataEmissao' => now()->format('d/m/Y H:i'),
            ];

            // Gera o PDF
            $pdf = PDF::loadView('medico.pdf.pedidoExames', $data);
            
            // Configurações do PDF
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ]);

            // Nome do arquivo
            $nomeArquivo = 'Pedido_Exames_' . 
                preg_replace('/[^a-zA-Z0-9]/', '_', $consulta->paciente->nomePaciente) . '_' . 
                \Carbon\Carbon::parse($consulta->dataConsulta)->format('Ymd') . '.pdf';

            // FORÇA O DOWNLOAD - Método 1 (recomendado)
            return $pdf->download($nomeArquivo);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }
}