<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\GrupoClientes;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class RemoverGrupoClientesRequest extends BaseValidationRequest
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
        return [
            'id' => ['required', 'integer', 'exists:tb_grupo_cliente,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O campo :attribute é obrigatório.',
            'id.integer'  => 'O campo :attribute deve ser um número inteiro.',
            'id.exists'   => 'O grupo de clientes não existe.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
