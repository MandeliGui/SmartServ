<?php

namespace App\Http\Requests\Tenant\EntradaSaida;

use App\Http\Requests\BaseValidationRequest;

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
            'tipo'             => data_get($this->data, 'tipo'),
            'data_vencimento'  => data_get($this->data, 'data_vencimento'),
            'valor_original'   => data_get($this->data, 'valor_original'),
            'quantidade_meses' => data_get($this->data, 'quantidade_meses'),
            'descricao'        => data_get($this->data, 'descricao'),
            'categoria_id'     => data_get($this->data, 'categoria_id'),
            'banco_id'         => data_get($this->data, 'banco_id'),
            'removido'         => data_get($this->data, 'removido', false),
        ];
    }

    public function rules(): array
    {
        return [
            'tipo'             => ['required', 'integer', 'in:1,2'],
            'data_vencimento'  => ['required', 'date'],
            'valor_original'   => ['required', 'numeric', 'min:0'],
            'quantidade_meses' => ['required', 'integer', 'min:1'],
            'descricao'        => ['nullable', 'string', 'max:255'],
            'categoria_id'     => ['required', 'integer', 'exists:tb_categoria_entrada_saida,id'],
            'banco_id'         => ['required', 'integer', 'exists:tb_bancos,id'],
            'removido'         => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required'             => ':attribute é obrigatório.',
            'tipo.integer'              => ':attribute deve ser um número inteiro.',
            'tipo.in'                   => ':attribute deve ser 1 (Entrada) ou 2 (Saída).',
            'data_vencimento.required'  => ':attribute é obrigatório.',
            'data_vencimento.date'      => ':attribute deve ser uma data válida.',
            'valor_original.required'   => ':attribute é obrigatório.',
            'valor_original.numeric'    => ':attribute deve ser um número.',
            'valor_original.min'        => ':attribute não pode ser negativo.',
            'quantidade_meses.required' => ':attribute é obrigatório.',
            'quantidade_meses.integer'  => ':attribute deve ser um número inteiro.',
            'quantidade_meses.min'      => ':attribute deve ser pelo menos 1.',
            'descricao.string'          => ':attribute deve ser uma string.',
            'descricao.max'             => ':attribute não pode ter mais de 255 caracteres.',
            'categoria_id.required'     => ':attribute é obrigatório.',
            'categoria_id.integer'      => ':attribute deve ser um número inteiro.',
            'categoria_id.exists'       => ':attribute selecionada não existe.',
            'banco_id.required'         => ':attribute é obrigatório.',
            'banco_id.integer'          => ':attribute deve ser um número inteiro.',
            'banco_id.exists'           => ':attribute selecionado não existe.',
            'removido.boolean'          => ':attribute deve ser verdadeiro ou falso.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
