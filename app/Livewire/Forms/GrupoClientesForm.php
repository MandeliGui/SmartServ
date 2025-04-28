<?php

declare(strict_types = 1);

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\GrupoClientesRequest;
use App\Services\Tenant\GrupoClientesService;
use Livewire\Form;

class GrupoClientesForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-grupo-cliente-create';
    public const MODAL_NAME_UPDATE                     = 'modal-grupo-cliente-update';
    public const MODAL_NAME_REMOVE                     = 'modal-grupo-cliente-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-grupo-cliente-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.grupo-cliente.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.grupo-cliente.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-grupo-cliente';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-grupo-cliente';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-grupo-cliente';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-grupo-cliente-remove-multiple';
    public const EVENT_PERSISTED                       = 'grupo-cliente-persisted';

    public mixed $id;

    public mixed $nome;

    private function attributes(): array
    {
        return [

            "nome" => "Nome",

        ];
    }

    public function create()
    {
        $data = GrupoClientesRequest::create($this->all(), $this->attributes())->validated();

        $servico = (new GrupoClientesService())->create($data);

        $this->reset();

        return $servico;
    }

    public function update()
    {
        $data = GrupoClientesRequest::update($this->all(), $this->attributes())->validated();

        $servico = (new GrupoClientesService())->update($data, $this->id);

        $this->reset();

        return $servico;
    }

    public function remove()
    {
        try {
            $data = GrupoClientesRequest::remove($this->id, $this->attributes())->validated();

            $servico = (new GrupoClientesService())->remove($data);

            $this->reset();

            return $servico;
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    public function setGrupo(mixed $id): void
    {
        $servico = (new GrupoClientesService())->findOne($id);

        $this->id   = $servico->id;
        $this->nome = $servico->nome;
    }
}
