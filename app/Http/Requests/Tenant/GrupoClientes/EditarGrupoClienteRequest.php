<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\GrupoClientes;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarGrupoClienteRequest extends BaseValidationRequest
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
            'nome' => $this->data['nome'],
        ];
    }

    public function rules(): array
    {
        return [
            'id'   => ['required', 'integer', 'exists:tb_grupo_cliente,id'],
            'nome' => ['required', 'string', 'max:255', 'unique:tb_grupo_cliente,nome,' . $this->data['id']],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'   => 'O campo :attribute é obrigatório.',
            'id.integer'    => 'O campo :attribute deve ser um número inteiro.',
            'id.exists'     => 'O grupo de clientes não existe.',
            'nome.required' => 'O campo :attribute é obrigatório.',
            'nome.string'   => 'O campo :attribute deve ser uma string.',
            'nome.max'      => 'O campo :attribute não pode ter mais de 255 caracteres.',
            'nome.unique'   => 'Já existe um grupo de clientes com esse nome.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
