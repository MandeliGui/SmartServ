<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\Tecnico;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use Validator;

class EditarTecnicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            "id"             => Helper::getIdByRequest($this->data, 'id'),
            "nome"           => str(data_get($this->data, "nome"))->trim()->toString(),
            "telefone"       => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "telefone")),
            "cpf"            => empty($this->data['cpf']) ? null : str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "cpf")),
            "email"          => empty($this->data['email']) ? null : str(data_get($this->data, "email"))->lower()->trim()->toString(),
            "dataNascimento" => data_get($this->data, "dataNascimento"),
            "endereco"       => [
                "cep"         => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "endereco.cep")),
                "rua"         => data_get($this->data, "endereco.rua"),
                "numero"      => data_get($this->data, "endereco.numero"),
                "bairro"      => data_get($this->data, "endereco.bairro"),
                "complemento" => data_get($this->data, "endereco.complemento"),
                "cidade"      => data_get($this->data, "endereco.cidade"),
                "uf"          => str(data_get($this->data, "endereco.uf"))->upper()->trim()->toString(),
            ],
        ];
    }

    public function rules(): array
    {
        return [
            "id"                   => ["required", "integer", "exists:tb_tecnicos,idTecnico"],
            'nome'                 => ['required', 'string', 'max:200'],
            'telefone'             => ['required', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:200'],
            'cpf'                  => ['required', 'string', 'max:11', 'min:11'],
            'dataNascimento'       => ['nullable', 'date'],
            'endereco.cep'         => ['nullable', 'string', 'max:8'],
            'endereco.rua'         => ['nullable', 'string', 'max:200'],
            'endereco.numero'      => ['nullable', 'string', 'max:10'],
            'endereco.bairro'      => ['nullable', 'string', 'max:100'],
            'endereco.complemento' => ['nullable', 'string'],
            'endereco.cidade'      => ['nullable', 'string'],
            'endereco.uf'          => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            "id.required"                 => ":attribute é obrigatório.",
            "id.integer"                  => ":attribute deve ser um número inteiro.",
            "id.exists"                   => "O :attribute informado não existe.",
            'nome.required'               => ':attribute é obrigatório.',
            'nome.string'                 => ':attribute deve ser uma string.',
            'nome.max'                    => ':attribute deve ter no máximo 200 caracteres.',
            'telefone.required'           => ':attribute é obrigatório.',
            'telefone.string'             => ':attribute deve ser uma string.',
            'telefone.max'                => ':attribute deve ter no máximo 20 caracteres.',
            'email.email'                 => ':attribute deve ser um email válido.',
            'email.max'                   => ':attribute deve ter no máximo 200 caracteres.',
            'cpf.required'                => ':attribute é obrigatório.',
            'cpf.string'                  => ':attribute deve ser uma string.',
            'cpf.max'                     => ':attribute deve ter no máximo 11 caracteres.',
            'cpf.min'                     => ':attribute deve ter no mínimo 11 caracteres.',
            'dataNascimento.date'         => ':attribute deve ser uma data válida.',
            'endereco.cep.string'         => ':attribute deve ser uma string.',
            'endereco.cep.max'            => ':attribute deve ter no máximo 8 caracteres.',
            'endereco.rua.string'         => ':attribute deve ser uma string.',
            'endereco.rua.max'            => ':attribute deve ter no máximo 200 caracteres.',
            'endereco.numero.string'      => ':attribute deve ser uma string.',
            'endereco.numero.max'         => ':attribute deve ter no máximo 10 caracteres.',
            'endereco.bairro.string'      => ':attribute deve ser uma string.',
            'endereco.bairro.max'         => ':attribute deve ter no máximo 100 caracteres.',
            'endereco.complemento.string' => ':attribute deve ser uma string.',
            'endereco.cidade.string'      => ':attribute deve ser uma string.',
            'endereco.uf.string'          => ':attribute deve ser uma string.',
        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
