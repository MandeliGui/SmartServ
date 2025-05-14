<?php

namespace App\Livewire\Forms;

use App\Helpers\Helper;
use App\Http\Requests\Tenant\MateriaisRequest;
use App\Services\Tenant\MateriaisService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MateriaisForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-material-create';
    public const MODAL_NAME_UPDATE                     = 'modal-material-update';
    public const MODAL_NAME_REMOVE                     = 'modal-material-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-material-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.materiais.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.materiais.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-material';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-material';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-material';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-material-remove-multiple';
    public const EVENT_PERSISTED                       = 'material-persisted';

    public mixed $id;

    public mixed $codigo;

    public mixed $nome;

    public mixed $descricao;

    public mixed $unidade = 'UN';

    public mixed $valor;

    private function attributes(): array
    {
        return [
            "codigo"    => "codigo",
            "nome"      => "nome",
            "descricao" => "descricao",
            "unidade"   => "unidade",
            "valor"     => "valor",
        ];
    }

    public function create()
    {


        $data = MateriaisRequest::create($this->all(), $this->attributes())->validated();

        $material = (new MateriaisService())->create($data);

        $this->reset();

        return $material;
    }

    public function update()
    {
        $data = MateriaisRequest::update($this->all(), $this->attributes())->validated();

        $material = (new MateriaisService())->update($data, $this->id);

        $this->reset();

        return $material;
    }

    public function remove()
    {
        $data = MateriaisRequest::remove($this->id, $this->attributes())->validated();

        $material = (new MateriaisService())->remove($data);

        $this->reset();

        return $material;
    }

    public function setMaterial(mixed $id): void
    {
        $material = (new MateriaisService())->findOne($id);

        if ($material) {
            $this->id        = $material->id;
            $this->codigo    = $material->codigo;
            $this->nome      = $material->nome;
            $this->descricao = $material->descricao;
            $this->unidade   = $material->unidade;
            $this->valor     = $material->valor;

        } else {
            $this->id        = null;
            $this->codigo    = null;
            $this->nome      = null;
            $this->descricao = null;
            $this->unidade   = null;
            $this->valor     = null;
        }
    }
}
