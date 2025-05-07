<?php

namespace App\Http\Requests\Tenant\FormaPagamento;

use App\Enums\Persistence;
use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class RemoverFormaPagamentoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id' => Helper::getIdByRequest($this->data, 'id'),
        ];
    }

    public function rules(): array
    {
        return Helper::getRulesOnlyIdOrMultipleIdsByRequest(Persistence::REMOVE, 'tb_formas_pagamento', 'id');
    }

    public function messages(): array
    {
        return Helper::getMessagesOnlyIdOrMultipleIdsByRequest(Persistence::REMOVE);
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
