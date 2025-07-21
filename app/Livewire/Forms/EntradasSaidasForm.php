<?php

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\EntradaSaidaRequest;
use App\Services\Tenant\EntradaSaidaService;
use App\Services\Tenant\FormaPagamentoService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EntradasSaidasForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-entrada-saida-create';
    public const MODAL_NAME_UPDATE                     = 'modal-entrada-saida-update';
    public const MODAL_NAME_REMOVE                     = 'modal-entrada-saida-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-entrada-saida-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.entrada-saida.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.entrada-saida.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-entrada-saida-remove-multiple';
    public const EVENT_PERSISTED                       = 'entrada-saida-persisted';


    public mixed $id;
    public mixed $tipo;
    public mixed $data_vencimento;
    public mixed $data_pagamento;
    public mixed $valor_original;
    public mixed $valor_pago;
    public mixed $quantidade_meses;
    public mixed $descricao;
    public mixed $categoria_id;
    public mixed $forma_pagamento_id;
    public mixed $banco_id;



    private function attributes(): array
    {
        return [

            'tipo'               => 'Tipo',
            'data_vencimento'    => 'Data de Vencimento',
            'data_pagamento'     => 'Data de Pagamento',
            'valor_original'     => 'Valor Original',
            'valor_pago'         => 'Valor Pago',
            'quantidade_meses'   => 'Quantidade de Meses',
            'descricao'          => 'Descrição',
            'categoria_id'       => 'Categoria',
            'forma_pagamento_id' => 'Forma de Pagamento',
            'banco_id'           => 'Banco',
        ];
    }

    public function create()
    {

        $data = EntradaSaidaRequest::create($this->all(), $this->attributes())->validated();


        $servico = (new EntradaSaidaService())->create($data);

        $this->reset();

        return $servico;
    }

    public function update()
    {
        $data = EntradaSaidaRequest::update($this->all(), $this->attributes())->validated();

        $servico = (new FormaPagamentoService())->update($data, $this->id);

        $this->reset();

        return $servico;
    }

    public function remove()
    {
        $data = EntradaSaidaRequest::remove($this->id, $this->attributes())->validated();

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
