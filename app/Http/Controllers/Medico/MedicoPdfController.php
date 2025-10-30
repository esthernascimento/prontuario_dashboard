<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Consulta;
use Carbon\Carbon; // Adicionado para uso consistente

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

            // 1. CORREÇÃO: Garante que a propriedade existe e trata como string vazia se for nula
            $exames = $consulta->examesSolicitados ?? '';
            
            // Verifica se existem exames (mantendo a lógica de negócio)
            if (trim($exames) === '') {
                // Se cair aqui, o PDF não será gerado e o usuário será redirecionado.
                // O problema é que o botão está visível/clicável no front-end sem dados.
                return redirect()->back()->with('error', 'Nenhum exame solicitado para esta consulta.');
            }

            // Prepara os dados para o PDF
            $data = [
                'paciente' => $consulta->paciente,
                // 3. MELHORIA: Mais robusto caso o relacionamento 'medico' seja nulo
                'medico' => $consulta->medico ?? (object)[
                    'nomeMedico' => $consulta->nomeMedico ?? 'N/A',
                    'crmMedico' => $consulta->crmMedico ?? 'N/A'
                ],
                'consulta' => $consulta,
                'exames' => $consulta->examesSolicitados,
                'numProntuario' => $consulta->prontuario ? 
                    str_pad($consulta->prontuario->idProntuarioPK, 6, '0', STR_PAD_LEFT) : 'N/A',
                // 2. MELHORIA: Uso de Carbon
                'dataEmissao' => Carbon::now()->format('d/m/Y H:i'),
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
                Carbon::parse($consulta->dataConsulta)->format('Ymd') . '.pdf'; // Uso de Carbon

            // FORÇA O DOWNLOAD - Método 1 (recomendado)
            return $pdf->download($nomeArquivo);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }
}
