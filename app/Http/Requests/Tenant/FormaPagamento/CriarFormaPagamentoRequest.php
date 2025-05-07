<?php

namespace App\Http\Requests\Tenant\FormaPagamento;

use App\Http\Requests\BaseValidationRequest;
use Validator;

class CriarFormaPagamentoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'nome'      => $this->data['nome'],
            'descricao' => $this->data['descricao'],
        ];
    }

    public function rules(): array
    {
        return [
            'nome'      => ['required', 'string', 'unique:tb_formas_pagamento'],
            'descricao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'    => ':attribute é obrigatório',
            'nome.string'      => ':attribute deve ser uma string',
            'nome.unique'      => ':attribute já está em uso',
            'descricao.string' => ':attribute deve ser uma string',
        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validate();
    }
}
