<?php

namespace App\Http\Requests\Tenant\EntradaSaida;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarEntradaSaidaRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'                 => Helper::getIdByRequest($this->data, 'id'),
            'tipo'               => data_get($this->data, 'tipo'),
            'data_vencimento'    => data_get($this->data, 'data_vencimento'),
            'valor_original'     => Helper::formatarDecimalDb(data_get($this->data, 'valor_original')),
            'data_pagamento'     => data_get($this->data, 'data_pagamento'),
            'valor_pago'         => data_get($this->data, 'valor_pago') !== null && data_get($this->data, 'valor_pago') !== ''
                ? Helper::formatarDecimalDb(data_get($this->data, 'valor_pago'))
                : null,
            'forma_pagamento_id' => data_get($this->data, 'forma_pagamento_id'),
            'descricao'          => data_get($this->data, 'descricao'),
            'categoria_id'       => data_get($this->data, 'categoria_id'),
            'banco_id'           => data_get($this->data, 'banco_id'),
            'id_fornecedor'      => data_get($this->data, 'id_fornecedor'),
        ];
    }

    public function rules(): array
    {
        return [
            'id'                 => ['required', 'integer', 'exists:tb_entradas_saidas,id'],
            'tipo'               => ['required', 'integer', 'in:1,2'],
            'data_vencimento'    => ['required', 'date'],
            'valor_original'     => ['required', 'numeric', 'min:0'],
            'data_pagamento'     => ['nullable', 'date'],
            'valor_pago'         => ['nullable', 'numeric', 'min:0'],
            'forma_pagamento_id' => ['nullable', 'integer', 'exists:tb_formas_pagamento,id'],
            'descricao'          => ['nullable', 'string', 'max:255'],
            'categoria_id'       => ['required', 'integer', 'exists:tb_categoria_entrada_saida,id'],
            'banco_id'           => ['required', 'integer', 'exists:tb_bancos,id'],
            'id_fornecedor'      => ['nullable', 'integer', 'exists:tb_fornecedores,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer'  => 'O campo :attribute deve ser um número inteiro.',
            'numeric'  => 'O campo :attribute deve ser um número válido.',
            'min'      => 'O campo :attribute deve ter no mínimo :min.',
            'max'      => 'O campo :attribute deve ter no máximo :max.',
            'date'     => 'O campo :attribute deve ser uma data válida.',
            'in'       => 'O campo :attribute possui um valor inválido.',
            'exists'   => 'O :attribute selecionado é inválido.',
            'string'   => 'O campo :attribute deve ser uma string.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
