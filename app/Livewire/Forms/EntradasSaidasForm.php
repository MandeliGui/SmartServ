<?php

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\EntradaSaidaRequest;
use App\Helpers\Helper;
use App\Services\Tenant\EntradaSaidaService;
use Livewire\Form;

class EntradasSaidasForm extends Form
{
    public const MODAL_NAME_CREATE                     = 'modal-entrada-saida-create';
    public const MODAL_NAME_UPDATE                     = 'modal-entrada-saida-update';
    public const MODAL_NAME_DAR_BAIXA                  = 'modal-entrada-saida-dar-baixa';
    public const MODAL_NAME_REMOVE                     = 'modal-entrada-saida-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-entrada-saida-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.entrada-saida.create-update';
    public const PATH_COMPONENT_FORM_DAR_BAIXA         = 'forms.entrada-saida.dar-baixa';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.entrada-saida.remove';
    public const EVENT_NAME_SHOW_MODAL_CREATE          = 'show-modal-create-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_UPDATE          = 'show-modal-update-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_DAR_BAIXA       = 'show-modal-dar-baixa-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-entrada-saida';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-entrada-saida-remove-multiple';
    public const EVENT_PERSISTED                       = 'entrada-saida-persisted';


    public mixed $id;
    public mixed $tipo;
    public mixed $data_vencimento;
    public mixed $data_pagamento;
    public mixed $valor_original;
    public mixed $valor_pago;
    public mixed $descricao;
    public mixed $categoria_id;
    public mixed $forma_pagamento_id;
    public mixed $banco_id;
    public mixed $quantidade_meses = 1;
    public mixed $situacao         = 0;
    public mixed $periodicidade;
    public mixed $id_fornecedor;
    public mixed $ordem_servico_id = null;


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
            'id_fornecedor'      => 'Fornecedor',
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

        $servico = (new EntradaSaidaService())->update($data, $this->id);

        $this->reset();

        return $servico;
    }

    public function darBaixa()
    {
        $data = EntradaSaidaRequest::darBaixa($this->all(), $this->attributes())->validated();


        $servico = (new EntradaSaidaService())->darBaixa($data);

        $this->reset();

        return $servico;
    }

    public function desfazerBaixa($id)
    {
        $servico = (new EntradaSaidaService())->desfazerBaixa($id);

        $this->reset();

        return $servico;
    }

    public function remove()
    {
        $data = EntradaSaidaRequest::remove($this->id, $this->attributes())->validated();


        $servico = (new EntradaSaidaService())->remove($data['id']);

        $this->reset();

        return $servico;
    }

    public function setEntradaSaida(mixed $id): void
    {
        $entradaSaida = (new EntradaSaidaService())->findOne($id);


        if ($entradaSaida) {
            $this->id                 = $entradaSaida->id;
            $this->tipo               = $entradaSaida->tipo;
            $this->data_vencimento    = $entradaSaida->data_vencimento;
            $this->data_pagamento     = $entradaSaida->data_pagamento;
            $this->valor_original     = Helper::formatarValorMonetarioPtBr((float) $entradaSaida->valor_original);
            $this->situacao           = $entradaSaida->status;
            $this->valor_pago         = $entradaSaida->valor_pago !== null ? Helper::formatarValorMonetarioPtBr((float) $entradaSaida->valor_pago) : null;
            $this->quantidade_meses   = $entradaSaida->quantidade_meses;
            $this->descricao          = $entradaSaida->descricao;
            $this->categoria_id       = $entradaSaida->categoria_id;
            $this->forma_pagamento_id = $entradaSaida->forma_pagamento_id;
            $this->ordem_servico_id   = $entradaSaida->ordem_servico_id;
            $this->banco_id           = $entradaSaida->banco_id;
            $this->id_fornecedor      = $entradaSaida->id_fornecedor;
        } else {
            $this->reset();
        }
    }
}
