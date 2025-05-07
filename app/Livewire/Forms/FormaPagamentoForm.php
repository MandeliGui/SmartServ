<?php

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\FormaPagamentoRequest;
use App\Services\Tenant\FormaPagamentoService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormaPagamentoForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-forma-pagamento-create';
    public const MODAL_NAME_UPDATE                     = 'modal-forma-pagamento-update';
    public const MODAL_NAME_REMOVE                     = 'modal-forma-pagamento-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-forma-pagamento-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.forma-pagamento.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.forma-pagamento.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-forma-pagamento';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-forma-pagamento';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-forma-pagamento';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-forma-pagamento-remove-multiple';
    public const EVENT_PERSISTED                       = 'forma-pagamento-persisted';

    public mixed $id;

    public mixed $codigo;

    public mixed $nome;

    public mixed $descricao;

    public mixed $valor;

    private function attributes(): array
    {
        return [

            "nome"      => "nome",
            "descricao" => "descricao",
        ];
    }

    public function create()
    {
        $data = FormaPagamentoRequest::create($this->all(), $this->attributes())->validated();

        $servico = (new FormaPagamentoService())->create($data);

        $this->reset();

        return $servico;
    }

    public function update()
    {
        $data = FormaPagamentoRequest::update($this->all(), $this->attributes())->validated();

        $servico = (new FormaPagamentoService())->update($data, $this->id);

        $this->reset();

        return $servico;
    }

    public function remove()
    {
        $data = FormaPagamentoRequest::remove($this->id, $this->attributes())->validated();

        $servico = (new FormaPagamentoService())->remove($data);

        $this->reset();

        return $servico;
    }

    public function setFormaPagamento(mixed $id): void
    {
        $formaPagamento = (new FormaPagamentoService())->findOne($id);

        if ($formaPagamento) {
            $this->id        = $formaPagamento->id;
            $this->nome      = $formaPagamento->nome;
            $this->descricao = $formaPagamento->descricao;
        } else {
            $this->id        = null;
            $this->nome      = null;
            $this->descricao = null;
        }
    }
}
