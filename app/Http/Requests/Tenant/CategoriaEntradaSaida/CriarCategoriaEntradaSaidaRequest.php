<?php

namespace App\Http\Requests\Tenant\CategoriaEntradaSaida;

use App\Enums\TipoEntradaSaida;
use App\Http\Requests\BaseValidationRequest;
use Illuminate\Validation\Rules\Enum;

class CriarCategoriaEntradaSaidaRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'nome'      => data_get($this->data, 'nome'),
            'tipo'      => data_get($this->data, 'tipo'),
            'descricao' => data_get($this->data, 'descricao'),
        ];
    }

    public function rules(): array
    {
        return [
            'nome'      => ['required', 'string'],
            'tipo'      => ['required', new Enum(TipoEntradaSaida::class)],
            'descricao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'    => ':attribute é obrigatório',
            'nome.string'      => ':attribute deve ser uma string',
            'tipo.required'    => ':attribute é obrigatório',
            'tipo.string'      => ':attribute deve ser uma string',
            'tipo.enum'        => ':attribute deve ser um valor válido de tipo de entrada/saída',
            'descricao.string' => ':attribute deve ser uma string',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
