<?php

namespace App\Http\Requests\Tenant\Auth;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class RegisterRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {

        $this->data = [
            "tipoPessoa"     => data_get($this->data, 'tipoPessoa'),
            "nomeCliente"    => data_get($this->data, 'nomeCliente'),
            "telefone"       => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, 'telefone')),
            "email"          => data_get($this->data, 'email'),
            "cpf"            => Helper::formatarCpfCnpj(data_get($this->data, 'cpf')),
            "cnpj"           => Helper::formatarCpfCnpj(data_get($this->data, 'cnpj')),
            "razaoSocial"    => data_get($this->data, 'razaoSocial'),
            "nomeFantasia"   => data_get($this->data, 'nomeFantasia'),
            "tipoEmpresa"    => data_get($this->data, 'tipoEmpresa'),
            "formaPagamento" => data_get($this->data, 'formaPagamento'),
            "anoAtual"       => data_get($this->data, 'anoAtual'),
            "endereco"       => [
                "cep"         => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "endereco.cep")),
                "rua"         => data_get($this->data, "endereco.rua"),
                "numero"      => data_get($this->data, "endereco.numero"),
                "bairro"      => data_get($this->data, "endereco.bairro"),
                "complemento" => data_get($this->data, "endereco.complemento"),
                "cidade"      => data_get($this->data, "endereco.cidade"),
                "uf"          => str(data_get($this->data, "endereco.uf"))->upper()->trim()->toString(),
            ],

            "dadosCartao" => [
                "nomeImpresso" => data_get($this->data, "dadosCartao.nomeImpresso"),
                "numeroCartao" => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "dadosCartao.numeroCartao")),
                "mesExpiracao" => data_get($this->data, "dadosCartao.mesExpiracao"),
                "anoExpiracao" => data_get($this->data, "dadosCartao.anoExpiracao"),
                "ccv"          => str()->replaceMatches('/[^0-9]/', '', data_get($this->data, "dadosCartao.ccv")),
            ]
        ];
    }

    public function rules(): array
    {

        return [
            'tipoPessoa'     => ['required', 'in:PF,PJ'],
            'nomeCliente'    => ['required', 'string', 'max:200'],
            'telefone'       => ['required', 'string', 'max:20'],
            'email'          => ['required', 'email', 'max:200'],
            'cpf'            => ['required_if:tipoPessoa,PF', 'exclude_if:tipoPessoa,PJ', 'string', 'size:14'],
            'cnpj'           => ['required_if:tipoPessoa,PJ', 'exclude_if:tipoPessoa,PF', 'string', 'size:18'],
            'razaoSocial'    => ['required_if:tipoPessoa,PJ', 'exclude_if:tipoPessoa,PF', 'string', 'max:200'],
            'nomeFantasia'   => ['required_if:tipoPessoa,PJ', 'exclude_if:tipoPessoa,PF', 'string', 'max:200'],
            'tipoEmpresa'    => ['required_if:tipoPessoa,PJ', 'exclude_if:tipoPessoa,PF', 'string', 'max:100'],
            'formaPagamento' => ['required', 'in:CREDIT_CARD,BOLETO'],
            'anoAtual'       => ['required', 'integer'],

            'endereco.cep'         => ['required', 'string', 'max:8'],
            'endereco.rua'         => ['required', 'string', 'max:200'],
            'endereco.numero'      => ['required', 'string', 'max:10'],
            'endereco.bairro'      => ['required', 'string', 'max:100'],
            'endereco.complemento' => ['nullable', 'string', 'max:150'],
            'endereco.cidade'      => ['required', 'string', 'max:100'],
            'endereco.uf'          => ['required', 'string', 'size:2'],

            'dadosCartao.nomeImpresso' => ['required_if:formaPagamento,CREDIT_CARD', 'exclude_if:formaPagamento,BOLETO', 'string', 'max:100'],
            'dadosCartao.numeroCartao' => ['required_if:formaPagamento,CREDIT_CARD', 'exclude_if:formaPagamento,BOLETO', 'string'],
            'dadosCartao.mesExpiracao' => ['required_if:formaPagamento,CREDIT_CARD', 'exclude_if:formaPagamento,BOLETO', 'string', 'size:2'],
            'dadosCartao.anoExpiracao' => ['required_if:formaPagamento,CREDIT_CARD', 'exclude_if:formaPagamento,BOLETO', 'string', 'size:4'],
            'dadosCartao.ccv'          => ['required_if:formaPagamento,CREDIT_CARD', 'exclude_if:formaPagamento,BOLETO', 'string', 'max:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => 'O campo :attribute é obrigatório.',
            'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
            'string'      => 'O campo :attribute deve ser uma string.',
            'max'         => 'O campo :attribute deve ter no máximo :max caracteres.',
            'size'        => 'O campo :attribute deve ter exatamente :size caracteres.',
            'in'          => 'O campo :attribute selecionado é inválido.',
            'email'       => 'O campo :attribute deve ser um endereço de email válido.',
            'integer'     => 'O campo :attribute deve ser um número inteiro.',
        ];

    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
