<?php

namespace App\Http\Requests\Tenant\FormaPagamento;


use App\Http\Requests\BaseValidationRequest;

class EditarFormaPagamentoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'        => $this->data['id'],
            'nome'      => $this->data['nome'],
            'descricao' => $this->data['descricao'],
        ];
    }

    public function rules(): array
    {
        return [
            'id'        => ['required', 'integer'],
            'nome'      => ['required', 'string', 'unique:tb_formas_pagamento,nome,' . $this->data['id']],
            'descricao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'      => ':attribute é obrigatório',
            'id.integer'       => ':attribute deve ser um número inteiro',
            'nome.required'    => ':attribute é obrigatório',
            'nome.string'      => ':attribute deve ser uma string',
            'nome.unique'      => ':attribute já está em uso',
            'descricao.string' => ':attribute deve ser uma string',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
