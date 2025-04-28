<?php

declare(strict_types = 1);

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\ServicosRequest;
use App\Services\Tenant\ServicosService;
use Livewire\Form;

class ServicosForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-servico-create';
    public const MODAL_NAME_UPDATE                     = 'modal-servico-update';
    public const MODAL_NAME_REMOVE                     = 'modal-servico-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-servico-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.servicos.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.servicos.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-servico';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-servico';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-servico';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-servico-remove-multiple';
    public const EVENT_PERSISTED                       = 'servico-persisted';

    public mixed $id;

    public mixed $codigo;

    public mixed $nome;

    public mixed $descricao;

    public mixed $valor;

    private function attributes(): array
    {
        return [
            "codigo"    => "codigo",
            "nome"      => "nome",
            "descricao" => "descricao",
            "valor"     => "valor",
        ];
    }

    public function create()
    {
        $data = ServicosRequest::create($this->all(), $this->attributes())->validated();

        $servico = (new ServicosService())->create($data);

        $this->reset();

        return $servico;
    }

    public function update()
    {
        $data = ServicosRequest::update($this->all(), $this->attributes())->validated();

        $servico = (new ServicosService())->update($data, $this->id);

        $this->reset();

        return $servico;
    }

    public function remove()
    {
        $data = ServicosRequest::remove($this->id, $this->attributes())->validated();

        $servico = (new ServicosService())->remove($data);

        $this->reset();

        return $servico;
    }

    public function setServico(mixed $id): void
    {
        $servico = (new ServicosService())->findOne($id);

        $this->id        = $servico->id;
        $this->codigo    = $servico->codigo;
        $this->nome      = $servico->nome;
        $this->descricao = $servico->descricao;
        $this->valor     = $servico->valor;
    }
}
