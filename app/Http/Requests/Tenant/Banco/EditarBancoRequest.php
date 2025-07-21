<?php

namespace App\Http\Requests\Tenant\Banco;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarBancoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'   => Helper::getIdByRequest($this->data, 'id'),
            'nome' => data_get($this->data, 'nome'),
        ];
    }

    public function rules(): array
    {
        return [
            'id'   => ['required', 'integer', 'exists:tb_bancos,id'],
            'nome' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'            => ':attribute é obrigatório.',
            'id.integer'             => ':attribute deve ser um número inteiro.',
            'id.exists'              => ':attribute não existe.',
            'nome.required'          => ':attribute é obrigatório.',
            'nome.string'            => ':attribute deve ser uma string.',
            'nome.max'               => ':attribute não pode ter mais de 255 caracteres.',
            'saldo_inicial.required' => ':attribute é obrigatório.',
            'saldo_inicial.numeric'  => ':attribute deve ser um número.',
            'saldo.required'         => ':attribute é obrigatório.',
            'saldo.numeric'          => ':attribute deve ser um número.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
