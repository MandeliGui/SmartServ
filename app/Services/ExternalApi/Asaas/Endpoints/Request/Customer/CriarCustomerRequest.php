<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Request\Customer;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use Validator;

class CriarCustomerRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'name'    => data_get($this->data, 'name'),
            'cpfCnpj' => preg_replace('/\D/', '', data_get($this->data, 'cpfCnpj')),

        ];
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100'],
            'cpfCnpj' => ['required', 'string', 'max:14', 'min:11'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string'   => 'O campo :attribute deve ser uma string.',
            'max'      => 'O campo :attribute deve ter no máximo :max caracteres.',
            'min'      => 'O campo :attribute deve ter no mínimo :min caracteres.',
        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
