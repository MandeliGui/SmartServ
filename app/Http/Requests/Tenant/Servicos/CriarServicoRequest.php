<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\Servicos;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use Validator;

class CriarServicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {

        $this->data = [
            'codigo'    => $this->data['codigo'],
            'nome'      => $this->data['nome'],
            'descricao' => $this->data['descricao'],
            'valor'     => Helper::formatarValorMonetarioDB((float)$this->data['valor']),
        ];
    }

    public function rules(): array
    {
        return [
            'codigo'    => ['required', 'integer', 'unique:tb_servicos'],
            'nome'      => ['required', 'string', 'unique:tb_servicos'],
            'descricao' => ['nullable', 'string'],
            'valor'     => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required'  => 'O campo :attribute é obrigatório',
            'codigo.integer'   => 'O campo :attribute deve ser um número inteiro',
            'codigo.unique'    => 'O campo :attribute já está em uso',
            'nome.required'    => 'O campo :attribute é obrigatório',
            'nome.string'      => 'O campo :attribute deve ser uma string',
            'nome.unique'      => 'O campo :attribute já está em uso',
            'descricao.string' => 'O campo :attribute deve ser uma string',
            'valor.required'   => 'O campo :attribute é obrigatório',
            'valor.numeric'    => 'O campo :attribute deve ser um número',

        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
