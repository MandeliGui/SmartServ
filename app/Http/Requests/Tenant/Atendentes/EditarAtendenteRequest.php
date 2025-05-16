<?php

namespace App\Http\Requests\Tenant\Atendentes;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use App\Rules\UniqueRule;
use Validator;

class EditarAtendenteRequest extends BaseValidationRequest
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
            'id'                   => ['required', 'integer', 'exists:tb_atendentes,idAtendente'],
            'nome'                 => ['required', 'string', 'max:200'],
            'telefone'             => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:200', new UniqueRule('tb_pessoas', 'email')],
            'cpf'                  => ['nullable', 'string', 'max:11', 'min:11', new UniqueRule('tb_pessoas', 'cpfCnpj')],
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
            'id.required'                 => ':attribute é obrigatório.',
            'id.integer'                  => ':attribute deve ser um número inteiro.',
            'id.exists'                   => ':attribute não existe.',
            'nome.required'               => ':attribute é obrigatório.',
            'email.email'                 => ':attribute deve ser um endereço de e-mail válido.',
            'cpf.max'                     => 'O :attribute não pode ter mais de 11 dígitos.',
            'dataNascimento.date'         => ':attribute deve ser uma data válida.',
            'endereco.cep.max'            => ':attribute não pode ter mais de 8 caracteres.',
            'endereco.rua.max'            => ':attribute não pode ter mais de 200 caracteres.',
            'endereco.numero.max'         => ':attribute não pode ter mais de 10 caracteres.',
            'endereco.bairro.max'         => ':attribute não pode ter mais de 100 caracteres.',
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
