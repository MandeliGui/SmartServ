<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdemServicoModel;
use Illuminate\Http\Request;

class OrdemServicoController extends Controller
{
    public function gerarPdf($id)
    {

        $ordemServico = OrdemServicoModel::findOrFail($id);
        $pdf          = \PDF::loadView('livewire.pages.tenant.ordem-servico.pdf', ['ordemServico' => $ordemServico, 'mostrarValores' => true]);
        return $pdf->stream('ordem-servico-' . $id . '.pdf');
    }

    public function gerarPdfSemValor($id)
    {


        $ordemServico = OrdemServicoModel::findOrFail($id);
        $pdf          = \PDF::loadView('livewire.pages.tenant.ordem-servico.pdf', ['ordemServico' => $ordemServico, 'mostrarValores' => false]);
        return $pdf->stream('ordem-servico-' . $id . '.pdf');
    }
}
