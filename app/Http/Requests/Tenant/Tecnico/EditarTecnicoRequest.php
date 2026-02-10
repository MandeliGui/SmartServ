<?php

declare(strict_types=1);

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
            'telefone'             => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:200'],
            'cpf'                  => ['nullable', 'string', 'max:11'],
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
            'required' => 'O campo :attribute é obrigatório.',
            'string'   => 'O campo :attribute deve ser uma string.',
            'integer'  => 'O campo :attribute deve ser um número inteiro.',
            'email'    => 'O campo :attribute deve ser um email válido.',
            'max'      => 'O campo :attribute deve ter no máximo :max caracteres.',
            'date'     => 'O campo :attribute deve ser uma data válida.',

        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
