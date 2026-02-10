<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Fornecedores;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use Validator;

class CriarFornecedorRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            "nomeRazaoSocial" => str(data_get($this->data, "nomeRazaoSocial"))->trim()->toString(),
            "nomeFantasia"    => str(data_get($this->data, "nomeFantasia"))->trim()->toString(),
            "atendente"       => str(data_get($this->data, "atendente"))->trim()->toString(),
            "cnpj"            => empty($this->data['cnpj']) ? null : str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "cnpj")),
            "telefone"        => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "telefone")),
            "email"           => empty($this->data['email']) ? null : str(data_get($this->data, "email"))->lower()->trim()->toString(),

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
            'atendente'            => ['nullable', 'string', 'max:200'],
            'cnpj'                 => ['nullable', 'string', 'max:14', 'min:11'],
            'telefone'             => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:200'],
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
            'max'      => 'O campo :attribute deve ter no máximo :max caracteres.',
            'min'      => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'email'    => 'O campo :attribute deve ser um email válido.',
            'date'     => 'O campo :attribute deve ser uma data válida.',
            'integer'  => 'O campo :attribute deve ser um número inteiro.',
            'exists'   => 'O valor selecionado para o campo :attribute é inválido.',
        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
