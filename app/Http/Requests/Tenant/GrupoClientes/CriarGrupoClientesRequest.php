<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\GrupoClientes;

use App\Http\Requests\BaseValidationRequest;

class CriarGrupoClientesRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'nome' => $this->data['nome'] ?? null,
        ];
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255', 'unique:tb_grupo_cliente,nome'],
        ];
    }

    public function messages(): array
    {
        return [
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
