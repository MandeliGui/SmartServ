<?php

namespace App\Http\Requests\Tenant\Materiais;

use App\Enums\UnidadesCompraMaterial;
use App\Http\Requests\BaseValidationRequest;
use Illuminate\Validation\Rules\Enum;

class CriarMateriaisRequest extends BaseValidationRequest
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
            'unidade'   => $this->data['unidade'],
            'valor'     => $this->data['valor'],
        ];
    }

    public function rules(): array
    {
        return [
            'codigo'    => ['required', 'string', 'max:255'],
            'nome'      => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'unidade'   => ['required', 'string', 'max:255', new Enum(UnidadesCompraMaterial::class)],
            'valor'     => ['required', 'numeric'],

        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required'  => ':attribute é obrigatório.',
            'codigo.string'    => ':attribute deve ser uma string.',
            'codigo.max'       => ':attribute deve ter no máximo 255 caracteres.',
            'nome.required'    => ':attribute é obrigatório.',
            'nome.string'      => ':attribute deve ser uma string.',
            'nome.max'         => ':attribute deve ter no máximo 255 caracteres.',
            'descricao.string' => ':attribute deve ser uma string.',
            'unidade.required' => ':attribute é obrigatório.',
            'unidade.string'   => ':attribute deve ser uma string.',
            'unidade.max'      => ':attribute deve ter no máximo 255 caracteres.',
            'unidade.enum'     => ':attribute não é válido.',
            'valor.required'   => ':attriobute é obrigatório.',
            'valor.numeric'    => ':attriobute deve ser um número.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validate();
    }
}
