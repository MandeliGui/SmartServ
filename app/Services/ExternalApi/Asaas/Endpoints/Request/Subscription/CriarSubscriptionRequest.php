<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Request\Subscription;

use App\Http\Requests\BaseValidationRequest;

class CriarSubscriptionRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'customer'    => data_get($this->data, 'customer'),
            'billingType' => data_get($this->data, 'billingType'),
            'value'       => data_get($this->data, 'value'),
            'nextDueDate' => data_get($this->data, 'nextDueDate'),
            'cycle'       => data_get($this->data, 'cycle', 'MONTHLY'),
        ];
    }

    public function rules(): array
    {
        return [
            'customer'    => ['required', 'string', 'max:50'],
            'billingType' => ['required', 'string', 'in:BOLETO,CREDIT_CARD'],
            'value'       => ['required', 'numeric'],
            'nextDueDate' => ['required', 'date_format:Y-m-d'],
            'cycle'       => ['required', 'string', 'in:MONTHLY'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => 'O campo :attribute é obrigatório.',
            'string'      => 'O campo :attribute deve ser uma string.',
            'max'         => 'O campo :attribute deve ter no máximo :max caracteres.',
            'in'          => 'O campo :attribute possui um valor inválido.',
            'numeric'     => 'O campo :attribute deve ser numérico.',
            'date_format' => 'O campo :attribute deve estar no formato :format.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
