<?php

namespace App\Http\Requests\Tenant\CategoriaEntradaSaida;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarCategoriaEntradaSaidaRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'        => Helper::getIdByRequest($this->data, 'id'),
            'nome'      => data_get($this->data, 'nome'),
            'descricao' => data_get($this->data, 'descricao'),
        ];
    }

    public function rules(): array
    {
        return [
            'id'        => ['required', 'integer', 'exists:tb_categoria_entrada_saida,id'],
            'nome'      => ['required', 'string'],

            'descricao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'      => ':attribute é obrigatório',
            'id.integer'       => ':attribute deve ser um número inteiro',
            'id.exists'        => ':attribute não existe',
            'nome.required'    => ':attribute é obrigatório',
            'nome.string'      => ':attribute deve ser uma string',
            'descricao.string' => ':attribute deve ser uma string',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
