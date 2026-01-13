<?php

namespace App\Http\Requests\Tenant\EntradaSaida;

use App\Enums\Tenant\PeriodicidadeEnum;
use App\Http\Requests\BaseValidationRequest;
use Illuminate\Validation\Rules\Enum;

class CriarEntradaSaidaRequest extends BaseValidationRequest
{

    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'tipo'               => data_get($this->data, 'tipo'),
            'data_vencimento'    => data_get($this->data, 'data_vencimento'),
            'valor_original'     => data_get($this->data, 'valor_original'),
            'quantidade_meses'   => data_get($this->data, 'quantidade_meses'),
            'periodicidade'      => data_get($this->data, 'periodicidade'),
            'data_pagamento'     => data_get($this->data, 'data_pagamento'),
            'valor_pago'         => data_get($this->data, 'valor_pago'),
            'forma_pagamento_id' => data_get($this->data, 'forma_pagamento_id'),
            'descricao'          => data_get($this->data, 'descricao'),
            'categoria_id'       => data_get($this->data, 'categoria_id'),
            'banco_id'           => data_get($this->data, 'banco_id'),
            'removido'           => data_get($this->data, 'removido', false),
        ];
    }

    public function rules(): array
    {
        return [
            'tipo'               => ['required', 'integer', 'in:1,2'],
            'data_vencimento'    => ['required', 'date'],
            'valor_original'     => ['required', 'numeric', 'min:0'],
            'quantidade_meses'   => ['required', 'integer', 'min:1'],
            'periodicidade'      => ['nullable', 'string', (new Enum(PeriodicidadeEnum::class))],
            'data_pagamento'     => ['nullable', 'date'],
            'valor_pago'         => ['nullable', 'numeric', 'min:0'],
            'forma_pagamento_id' => ['nullable', 'integer', 'exists:tb_formas_pagamento,id'],
            'descricao'          => ['nullable', 'string', 'max:255'],
            'categoria_id'       => ['required', 'integer', 'exists:tb_categoria_entrada_saida,id'],
            'banco_id'           => ['required', 'integer', 'exists:tb_bancos,id'],
            'removido'           => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'  => 'O campo :attribute é obrigatório.',
            'integer'   => 'O campo :attribute deve ser um número inteiro.',
            'numeric'   => 'O campo :attribute deve ser um número válido.',
            'min'       => 'O campo :attribute deve ter no mínimo :min.',
            'max'       => 'O campo :attribute deve ter no máximo :max.',
            'date'      => 'O campo :attribute deve ser uma data válida.',
            'in'        => 'O campo :attribute possui um valor inválido.',
            'exists'    => 'O :attribute selecionado é inválido.',
            'boolean'   => 'O campo :attribute deve ser um booleano.',
            'string'    => 'O campo :attribute deve ser uma string.',
            Enum::class => 'O campo :attribute deve ser um ' . PeriodicidadeEnum::class,

        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
