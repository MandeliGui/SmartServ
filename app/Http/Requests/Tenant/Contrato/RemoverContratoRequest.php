<?php

namespace App\Http\Requests\Tenant\Contrato;

use App\Http\Requests\BaseValidationRequest;

class RemoverContratoRequest extends BaseValidationRequest

{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            "id" => data_get($this->data, "id"),
        ];
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:tb_contratos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => ':attribute é obrigatório.',
            'id.integer'  => ':attribute deve ser um número inteiro.',
            'id.exists'   => ':attribute informado não existe na tabela de contratos.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
