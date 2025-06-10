<?php

namespace App\Livewire\Forms;

use App\Enums\StatusOrdemServico;
use App\Enums\TipoOrdemServico;
use App\Helpers\Helper;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Http\Requests\Tenant\OrdemServicoRequest;
use App\Models\ClienteModel;
use App\Models\ServicosModel;
use App\Services\Tenant\OrdemServicoService;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OrdemServicoForm extends Form
{
    public const MODAL_NAME_CREATE = 'modal-ordem-servico-create';
    public const MODAL_NAME_UPDATE = 'modal-ordem-servico-update';
    public const MODAL_NAME_REMOVE = 'modal-ordem-servico-remove';


    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.ordem-servico.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.ordem-servico.remove';


    public const EVENT_NAME_SHOW_MODAL_CREATE = 'show-modal-create-ordem-servico';
    public const EVENT_NAME_SHOW_MODAL_UPDATE = 'show-modal-update-ordem-servico';
    public const EVENT_NAME_SHOW_MODAL_REMOVE = 'show-modal-remove-ordem-servico';


    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-ordem-servico-remove-multiple';
    public const EVENT_PERSISTED                       = 'ordem-servico-persisted';

    public mixed $id;

    public mixed $codigo;
    public mixed $tipo = TipoOrdemServico::ORDEM_SERVICO->value;
    public mixed $dataAbertura;
    public mixed $dataEntrega;

    public mixed $status = StatusOrdemServico::PENDENTE->value;
    public mixed $valorTotal;

    public mixed $idCliente;
    public mixed $nomeCliente;

    public mixed $idTecnico;

    public mixed $idAtendente;

    public mixed $materiais = [];

    public mixed $servicos = [];

    public mixed $clientes = [];


    private function attributes(): array
    {
        return [
            "codigo"               => "codigo",
            "tipo"                 => "tipo",
            "dataAbertura"         => "dataAbertura",
            "dataEntrega"          => "dataEntrega",
            "status"               => "status",
            "valorTotal"           => "valorTotal",
            "idCliente"            => "idCliente",
            "idTecnico"            => "idTecnico",
            "idAtendente"          => "idAtendente",
            "materiais.id"         => "id Servico",
            "materiais.quantidade" => "quantidade material",
            "servicos.id"          => "id Servico",
            "servicos.quantidade"  => "quantidade servico",

        ];
    }


    public function create()
    {
        $data = OrdemServicoRequest::create($this->all(), $this->attributes())->validated();


        $ordemServico = (new OrdemServicoService())->create($data);
        Flux::toast('Ordem de servico criada com sucesso!', variant: 'success');

    }

    public function update()
    {
        $data = OrdemServicoRequest::update($this->all(), $this->attributes())->validated();

        $ordemServico = (new OrdemServicoService())->update($data, $this->id);

        Flux::toast('Ordem de servico editada com sucesso!', variant: 'success');

    }

    public function remove()
    {
        $data = OrdemServicoRequest::remove($this->id, $this->attributes())->validated();

        $ordemServico = (new OrdemServicoService())->remove($data);

        $this->reset();

        return $ordemServico;
    }

    public function editarMaterial($material)
    {

        $data         = OrdemServicoRequest::editarMaterial($material, $this->attributes())->validated();
        $ordemServico = (new OrdemServicoService())->editarMaterial($data);

        $this->setOrdemServico($this->id);
        Flux::toast('Material editado com sucesso!', variant: 'success');



    }

    public function removeMaterial(mixed $id)
    {


        $data = OrdemServicoRequest::removeMaterial($id, $this->attributes())->validated();

        $ordemServico = (new OrdemServicoService())->removeMaterial($data);

        $this->setOrdemServico($this->id);
        Flux::toast('Material removido com sucesso!', variant: 'success');


    }

    public function editarServico($servico)
    {

        $data = OrdemServicoRequest::editarServico($servico, $this->attributes())->validated();

        $ordemServico = (new OrdemServicoService())->editarServico($data);

        $this->setOrdemServico($this->id);

        Flux::toast('Servico editado com sucesso!', variant: 'success');

        return $ordemServico;
    }


    public function removeServico(mixed $id)
    {


        $data = OrdemServicoRequest::removeServico($id, $this->attributes())->validated();

        $ordemServico = (new OrdemServicoService())->removeServico($data);

        $this->setOrdemServico($this->id);

        Flux::toast('Servico removido com sucesso!', variant: 'success');

        return $ordemServico;
    }

    public function setOrdemServico(mixed $id): void
    {

        $ordemServico = (new OrdemServicoService())->findOne($id);



        $this->id           = $ordemServico->id;
        $this->codigo       = $ordemServico->codigo;
        $this->tipo         = $ordemServico->tipo;
        $this->dataAbertura = $ordemServico->dataAbertura;
        $this->dataEntrega  = $ordemServico->dataEntrega;
        $this->status       = $ordemServico->status;
        $this->valorTotal   = $ordemServico->valorTotal;
        $this->idCliente    = $ordemServico->idCliente;
        $this->nomeCliente  = $ordemServico->cliente->pessoa->nomeFantasia ?: $ordemServico->cliente->pessoa->nomeRazaoSocial;
        $this->idTecnico    = $ordemServico->idTecnico;
        $this->idAtendente  = $ordemServico->idAtendente;


        $this->materiais = $ordemServico->materiais()->get()->map(function ($material) {

            return [
                'id'            => $material->pivot->id,
                'codigo'        => $material->codigo,
                'nome'          => $material->nome,
                'valorUnitario' => $material->pivot->valorUnitario,
                'quantidade'    => $material->pivot->quantidade,
                'valorTotal'    => $material->pivot->valorTotal,
            ];
        })->toArray();

        $this->servicos = $ordemServico->servicos->map(function ($servico) {
            return [
                'id'            => $servico->pivot->id,
                'codigo'        => $servico->codigo,
                'nome'          => $servico->nome,
                'valorUnitario' => $servico->pivot->valorUnitario,
                'quantidade'    => $servico->pivot->quantidade,
                'valorTotal'    => $servico->pivot->valorTotal,
            ];
        })->toArray();


    }


    public function mount()
    {
        $this->dataAbertura = now()->format('Y-m-d');
    }
}
