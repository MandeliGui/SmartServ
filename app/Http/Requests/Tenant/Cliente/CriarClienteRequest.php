<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\Cliente;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use Validator;

class CriarClienteRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [

            'id'              => Helper::getIdByRequest($this->data, 'id'),
            "nomeRazaoSocial" => str(data_get($this->data, "nomeRazaoSocial"))->trim()->toString(),
            "nomeFantasia"    => str(data_get($this->data, "nomeFantasia"))->trim()->toString(),
            "telefone"        => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "telefone")),
            "cpfCnpj"         => empty($this->data['cpfCnpj']) ? null : str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "cpfCnpj")),
            "email"           => empty($this->data['email']) ? null : str(data_get($this->data, "email"))->lower()->trim()->toString(),
            "dataNascimento"  => data_get($this->data, "dataNascimento"),
            "tipoPessoa"      => data_get($this->data, 'cpfCnpj') ? (strlen((string) preg_replace('/[^0-9]/', '', (string) data_get($this->data, "cpfCnpj"))) == 11 ? "PF" : "PJ") : null,

            "idGrupo" => Helper::getIdByRequest($this->data, 'idGrupo'),

            "endereco" => [
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
            'nomeRazaoSocial'      => ['required', 'string', 'max:200'],
            'nomeFantasia'         => ['nullable', 'string', 'max:200'],
            'telefone'             => ['required', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:200'],
            'cpfCnpj'              => ['nullable', 'string', 'max:14', 'min:11'],
            'dataNascimento'       => ['required', 'date'],
            'idGrupo'              => ['nullable', 'integer', 'exists:tb_grupo_cliente,id_grupo'],
            'endereco.cep'         => ['required', 'string', 'max:8'],
            'endereco.rua'         => ['required', 'string', 'max:200'],
            'endereco.numero'      => ['required', 'string', 'max:10'],
            'endereco.bairro'      => ['required', 'string', 'max:100'],
            'endereco.complemento' => ['nullable', 'string'],
            'endereco.cidade'      => ['required', 'string'],
            'endereco.uf'          => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomeRazaoSocial.required'    => 'O campo :attribute social é obrigatório.',
            'nomeRazaoSocial.string'      => 'O campo :attribute social deve ser uma string.',
            'nomeRazaoSocial.max'         => 'O campo :attribute social deve ter no máximo 200 caracteres.',
            'nomeFantasia.string'         => 'O campo :attribute deve ser uma string.',
            'nomeFantasia.max'            => 'O campo :attribute deve ter no máximo 200 caracteres.',
            'telefone.required'           => 'O campo :attribute é obrigatório.',
            'telefone.string'             => 'O campo :attribute deve ser uma string.',
            'telefone.max'                => 'O campo :attribute deve ter no máximo 20 caracteres.',
            'email.email'                 => 'O campo :attribute deve ser um email válido.',
            'email.max'                   => 'O campo :attribute deve ter no máximo 200 caracteres.',
            'cpfCnpj.string'              => 'O campo :attribute deve ser uma string.',
            'cpfCnpj.max'                 => 'O campo :attribute deve ter no máximo 14 caracteres.',
            'cpfCnpj.min'                 => 'O campo :attribute deve ter no mínimo 11 caracteres.',
            'dataNascimento.required'     => 'O campo :attribute é obrigatório.',
            'dataNascimento.date'         => 'O campo :attribute deve ser uma data válida.',
            'idGrupo.integer'             => 'O campo :attribute deve ser um número inteiro.',
            'idGrupo.exists'              => 'O :attribute informado não existe.',
            'endereco.cep.required'       => 'O campo :attribute é obrigatório.',
            'endereco.cep.string'         => 'O campo :attribute deve ser uma string.',
            'endereco.cep.max'            => 'O campo :attribute deve ter no máximo 8 caracteres.',
            'endereco.rua.required'       => 'O campo :attribute é obrigatório.',
            'endereco.rua.string'         => 'O campo :attribute deve ser uma string.',
            'endereco.rua.max'            => 'O campo :attribute deve ter no máximo 200 caracteres.',
            'endereco.numero.required'    => 'O campo :attribute é obrigatório.',
            'endereco.numero.string'      => 'O campo :attribute deve ser uma string.',
            'endereco.numero.max'         => 'O campo :attribute deve ter no máximo 10 caracteres.',
            'endereco.bairro.required'    => 'O campo :attribute é obrigatório.',
            'endereco.bairro.string'      => 'O campo :attribute deve ser uma string.',
            'endereco.bairro.max'         => 'O campo :attribute deve ter no máximo 100 caracteres.',
            'endereco.complemento.string' => 'O campo :attribute deve ser uma string.',
            'endereco.cidade.required'    => 'O campo :attribute é obrigatório.',
            'endereco.cidade.string'      => 'O campo :attribute deve ser uma string.',
            'endereco.uf.required'        => 'O campo :attribute é obrigatório.',
            'endereco.uf.string'          => 'O campo :attribute deve ser uma string.',
        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
