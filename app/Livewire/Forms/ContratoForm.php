<?php

namespace App\Livewire\Forms;

use App\Enums\StatusOrdemServico;
use App\Enums\TipoOrdemServico;
use App\Http\Requests\Tenant\ContratoRequest;
use App\Http\Requests\Tenant\OrdemServicoRequest;
use App\Services\Tenant\ContratosService;
use App\Services\Tenant\OrdemServicoService;
use Carbon\Carbon;
use Flux\Flux;
use Livewire\Form;

class ContratoForm extends Form
{
    public const MODAL_NAME_CREATE = 'modal-contrato-create';
    public const MODAL_NAME_UPDATE = 'modal-contrato-update';
    public const MODAL_NAME_REMOVE = 'modal-contrato-remove';


    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.contratos.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.contratos.remove';


    public const EVENT_NAME_SHOW_MODAL_CREATE = 'show-modal-create-contrato';
    public const EVENT_NAME_SHOW_MODAL_UPDATE = 'show-modal-update-contrato';
    public const EVENT_NAME_SHOW_MODAL_REMOVE = 'show-modal-remove-contrato';


    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-contrato-remove-multiple';
    public const EVENT_PERSISTED                       = 'contrato-persisted';

    public ?int $id = null;

    public string $periodicidade;
    public mixed  $status = 'ATIVO';
    public mixed  $valor;

    public mixed $idCliente;
    public mixed $materiais = [];

    public mixed $servicos = [];

    public mixed $clientes = [];
    public mixed $dataInicioContrato;


    private function attributes(): array
    {
        return [
            'id'                 => 'ID',
            'periodicidade'      => 'Periodicidade',
            'status'             => 'Status',
            'valor'              => 'Valor',
            'idCliente'          => 'Cliente',
            'dataInicioContrato' => 'Data de Início do Contrato',

        ];
    }


    public function create()
    {
        try {

            $data = ContratoRequest::create($this->all(), $this->attributes())->validated();


            $contrato = (new ContratosService())->create($data);
            Flux::toast('Contrato criado com sucesso!', variant: 'success');
        } catch (\Exception $exception) {
            dd($exception);
        }

    }

    public function update()
    {
        try {
            $data = ContratoRequest::update($this->all(), $this->attributes())->validated();


            $contrato = (new ContratosService())->update($data, $this->id);

            Flux::toast('Contrato editado com sucesso!', variant: 'success');
        } catch (\Throwable $e) {
            dd($e);
        }

    }

    public function remove()
    {

        $data = ContratoRequest::remove($this->id, $this->attributes())->validated();


        $contrato = (new ContratosService())->remove($data);

        $this->resetExcept('periodicidade');

        return $contrato;

    }

    public function editarMaterial($material)
    {

        $data     = ContratoRequest::editarMaterial($material, $this->attributes())->validated();
        $contrato = (new ContratosService())->editarMaterial($data);

        $this->setContrato($this->id);
        Flux::toast('Material editado com sucesso!', variant: 'success');


    }

    public function removeMaterial(mixed $id)
    {


        $data = ContratoRequest::removeMaterial($id, $this->attributes())->validated();

        $contrato = (new ContratosService())->removeMaterial($data);

        $this->setContrato($this->id);
        Flux::toast('Material removido com sucesso!', variant: 'success');


    }

    public function editarServico($servico)
    {

        try {

            $data = ContratoRequest::editarServico($servico, $this->attributes())->validated();

            $contrato = (new ContratosService())->editarServico($data);

            $this->setContrato($this->id);

            Flux::toast('Servico editado com sucesso!', variant: 'success');

            return $contrato;
        } catch (\Exception $exception) {
            dd($exception);
        }
    }


    public function removeServico(mixed $id)
    {


        $data = ContratoRequest::removeServico($id, $this->attributes())->validated();

        $contrato = (new ContratosService())->removeServico($data);

        $this->setContrato($this->id);

        Flux::toast('Servico removido com sucesso!', variant: 'success');

        return $contrato;
    }


    public function setContrato(mixed $id): void
    {

        $contrato = (new ContratosService())->findOne($id);


        $this->id                 = $contrato->id;
        $this->idCliente          = $contrato->id_cliente;
        $this->periodicidade      = $contrato->periodicidade;
        $this->valor              = $contrato->valor;
        $this->status             = $contrato->status;
        $this->dataInicioContrato = $contrato->data_inicio_contrato;


        $this->materiais = $contrato->materiais()->get()->map(function ($material) {

            return [
                'id'            => $material->pivot->id,
                'codigo'        => $material->codigo,
                'nome'          => $material->nome,
                'valorUnitario' => $material->pivot->valorUnitario,
                'quantidade'    => $material->pivot->quantidade,
                'descricao'     => $material->pivot->descricao,
                'valorTotal'    => $material->pivot->valorTotal,
            ];
        })->toArray();

        $this->servicos = $contrato->servicos->map(function ($servico) {
            return [
                'id'            => $servico->pivot->id,
                'codigo'        => $servico->codigo,
                'nome'          => $servico->nome,
                'valorUnitario' => $servico->pivot->valorUnitario,
                'quantidade'    => $servico->pivot->quantidade,
                'descricao'     => $servico->pivot->descricao,
                'valorTotal'    => $servico->pivot->valorTotal,
            ];
        })->toArray();


    }

    public function gerarOrdensServico($contratos)
    {
        foreach ($contratos as $item) {
            $ordemJaGerada = false;

            try {

                if ($item->ordemServico->isNotEmpty()) {

                    $ultimoAtendimento = Carbon::parse($item->ordemServico->last()->dataAbertura)->format('Y-m');
                    $dataAtual         = Carbon::now()->format('Y-m');

                    if ($ultimoAtendimento === $dataAtual) {
                        $ordemJaGerada = true;
                    }
                }
                if (!$ordemJaGerada) {

                    $ultimoCodigoOrdemServico = (int)(new \App\Models\OrdemServicoModel())->orderBy('id', 'desc')->first()->codigo + 1 ?? 1;
                    $body                     = [
                        "id"           => null,
                        "codigo"       => "$ultimoCodigoOrdemServico",
                        "tipo"         => TipoOrdemServico::ORDEM_SERVICO->value,
                        "dataAbertura" => now()->format('Y-m-d'),
                        "dataEntrega"  => null,
                        "status"       => StatusOrdemServico::PENDENTE->value,
                        "valorTotal"   => $item->valor,
                        "idCliente"    => $item->id_cliente,
                        "nomeCliente"  => null,
                        "idTecnico"    => null,
                        "idAtendente"  => null,
                        "contratoId"   => $item->id,
                        "materiais"    => $item->materiais->map(function ($material) {
                            return [
                                "id"            => null,
                                "idMaterial"    => $material->id,
                                "codigo"        => $material->codigo,
                                "quantidade"    => $material->pivot->quantidade,
                                "descricao"     => $material->pivot->descricao,
                                "nome"          => $material->nome,
                                "valorUnitario" => $material->pivot->valorUnitario,
                                "valorTotal"    => $material->pivot->valorTotal,
                            ];
                        })->toArray(),
                        "servicos"     => $item->servicos->map(function ($servico) {
                            return [
                                "id"            => null,
                                "idServico"     => $servico->id,
                                "codigo"        => $servico->codigo,
                                "quantidade"    => $servico->pivot->quantidade,
                                "descricao"     => $servico->pivot->descricao,
                                "nome"          => $servico->nome,
                                "valorUnitario" => $servico->pivot->valorUnitario,
                                "valorTotal"    => $servico->pivot->valorTotal,
                            ];
                        })->toArray(),
                    ];
                    $data                     = OrdemServicoRequest::create($body)->validated();
                    (new OrdemServicoService())->create($data);
                }
                Flux::toast('Ordens de serviço criadas com sucesso!', variant: 'success');

            } catch (\Throwable $e) {
                dd($e);
            }
        }
    }


    public function mount()
    {
        $this->dataInicioContrato = now()->format('Y-m-d');
    }
}
