<?php

namespace App\Http\Requests\Tenant\EntradaSaida;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class DarBaixaEntradaSaidaRequest extends BaseValidationRequest
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
            'data_pagamento'     => data_get($this->data, 'data_pagamento'),
            'valor_pago'         => data_get($this->data, 'valor_pago'),
            'forma_pagamento_id' => data_get($this->data, 'forma_pagamento_id'),
            'banco_id'           => data_get($this->data, 'banco_id'),
        ];


    }

    public function rules(): array
    {

        return [
            'id'                 => ['required', 'integer', 'exists:tb_entradas_saidas,id'],
            'data_pagamento'     => ['required', 'date'],
            'valor_pago'         => ['required', 'numeric', 'min:0'],
            'forma_pagamento_id' => ['required', 'integer'],
            'banco_id'           => ['required', 'integer'],
        ];

    }

    public function messages(): array
    {
        return [
            'id.required'                 => ':attribute é obrigatório.',
            'id.integer'                  => ':attribute deve ser um número inteiro.',
            'id.exists'                   => ':attribute não existe.',
            'data_pagamento.required'     => ':attribute é obrigatória.',
            'data_pagamento.date'         => ':attribute deve ser uma data válida.',
            'valor_pago.required'         => ':attribute é obrigatório.',
            'valor_pago.numeric'          => ':attribute deve ser um número.',
            'valor_pago.min'              => ':attribute não pode ser negativo.',
            'forma_pagamento_id.required' => ':attribute é obrigatória.',
            'forma_pagamento_id.integer'  => ':attribute deve ser um número inteiro.',
            'forma_pagamento_id.exists'   => ':attribute informada não existe.',
            'banco_id.required'           => ':attribute obrigatório.',
            'banco_id.integer'            => ':attribute deve ser um número inteiro.',
            'banco_id.exists'             => ':attribute informado não existe.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
