<?php

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\CategoriaEntradaSaidaRequest;
use App\Services\Tenant\CategoriaEntradaSaidaService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoriasEntradaSaidaForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-categoria-entrada-saida-create';
    public const MODAL_NAME_UPDATE                     = 'modal-categoria-entrada-saida-update';
    public const MODAL_NAME_REMOVE                     = 'modal-categoria-entrada-saida-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-categoria-entrada-saida-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.categoria-entrada-saida.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.categoria-entrada-saida.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-categoria-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-categoria-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-categoria-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-categoria-entrada-saida-remove-multiple';
    public const EVENT_PERSISTED                       = 'categoria-entrada-saida-persisted';

    public mixed $id;

    public ?string $nome;
    public ?int    $tipo;
    public ?string $descricao;

    private function attributes(): array
    {
        return [

            "nome"      => "Nome",
            "tipo"      => "Tipo",
            "descricao" => "Descrição",
        ];
    }

    public function create()
    {
        $data = CategoriaEntradaSaidaRequest::create($this->all(), $this->attributes())->validated();

        $categoria = (new CategoriaEntradaSaidaService())->create($data);

        $this->reset();

        return $categoria;
    }

    public function update()
    {
        $data = CategoriaEntradaSaidaRequest::update($this->all(), $this->attributes())->validated();

        $categoria = (new CategoriaEntradaSaidaService())->update($data, $this->id);

        $this->reset();

        return $categoria;
    }

    public function remove()
    {
        $data = CategoriaEntradaSaidaRequest::remove($this->id, $this->attributes())->validated();

        $categoria = (new CategoriaEntradaSaidaService())->remove($data);

        $this->reset();

        return $categoria;
    }

    public function setCategoriaEntradaSaida(mixed $id): void
    {
        $categoria = (new CategoriaEntradaSaidaService())->findOne($id);


        if ($categoria) {
            $this->id        = $categoria->id;
            $this->nome      = $categoria->nome;
            $this->tipo      = $categoria->tipo;
            $this->descricao = $categoria->descricao;
        } else {
            $this->id        = null;
            $this->nome      = null;
            $this->tipo      = null;
            $this->descricao = null;
        }
    }
}
