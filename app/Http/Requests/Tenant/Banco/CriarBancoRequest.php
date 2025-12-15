<?php

namespace App\Http\Requests\Tenant\Banco;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class CriarBancoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'nome'          => data_get($this->data, 'nome'),
            'saldo_inicial' => Helper::formatarDecimalDb(data_get($this->data, 'saldo_inicial', 0))
        ];

    }

    public function rules(): array
    {
        return [
            'nome'          => ['required', 'string', 'max:255'],
            'saldo_inicial' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'          => ':attribute é obrigatório.',
            'nome.string'            => ':attribute deve ser uma string.',
            'nome.max'               => ':attribute não pode ter mais de 255 caracteres.',
            'saldo_inicial.required' => ':attribute é obrigatório.',
            'saldo_inicial.numeric'  => ':attribute deve ser um número.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
