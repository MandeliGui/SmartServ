<?php

namespace App\Http\Requests\Tenant\OrdemServico;

use App\Enums\CondicoesPagamento;
use App\Enums\StatusOrdemServico;
use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\OrdemServicoRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;

class FinalizarOrdemServicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $input = $this->data;

        $parcelasRaw = data_get($input, 'parcelas', []);

        $parcelas = array_map(function($p) {
            $p = (array) $p;
            $p['valor'] = Helper::formatarDecimalDb(data_get($p, 'valor'));
            return $p;
        }, $parcelasRaw);


        $this->data = [
            'id'                 => Helper::getIdByRequest($input, 'id'),
            'status'             => StatusOrdemServico::FINALIZADO->value,
            'dataEntrega'        => Carbon::now()->format('Y-m-d'),
            'parcelas'           => $parcelas,
            'condicoesPagamento' => data_get($input, 'condicoesPagamento'),
            'quantidadeParcela'  => data_get($input, 'quantidadeParcela'),
            'bancoId'            => data_get($input, 'bancoId'),
            'valor'              => Helper::formatarValorMonetarioDB(data_get($input, 'valorTotal')),
            'formaPagamentoId'   => data_get($input, 'formaPagamentoId'),
        ];
    }

    public function rules(): array
    {

        return [
            'id'                        => ['required', 'integer', 'exists:tb_ordem_servico,id'],
            'status'                    => ['required', 'string', 'in:' . StatusOrdemServico::FINALIZADO->value],
            'dataEntrega'               => ['required', 'date_format:Y-m-d'],
            'parcelas'                  => ['required', 'array', 'min:1'],
            'parcelas.*.dataVencimento' => ['required', 'date_format:Y-m-d'],
            'parcelas.*.valor'          => ['required', 'numeric', 'min:0'],
            'condicoesPagamento'        => ['required', 'string', new Enum(CondicoesPagamento::class)],
            'quantidadeParcela'         => ['required', 'integer', 'min:1'],
            'bancoId'                   => ['required', 'integer', 'exists:tb_bancos,id'],
            'valor'                     => ['required', 'numeric', 'min:0'],
            'formaPagamentoId'          => ['required', 'integer', 'exists:tb_formas_pagamento,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'                           => ':attribute é obrigatório.',
            'id.integer'                            => ':attribute deve ser um número inteiro.',
            'id.exists'                             => ':attribute informado não existe na tabela de ordens de serviço.',
            'status.required'                       => ':attribute é obrigatório.',
            'status.string'                         => ':attribute deve ser uma string.',
            'status.in'                             => ':attribute deve ser um dos seguintes valores: ' . StatusOrdemServico::FINALIZADO->value,
            'dataEntrega.required'                  => ':attribute é obrigatório.',
            'dataEntrega.date_format'               => ':attribute deve estar no formato Y-m-d.',
            'parcelas.required'                     => ':attribute é obrigatório.',
            'parcelas.array'                        => ':attribute deve ser um array.',
            'parcelas.min'                          => ':attribute deve ter pelo menos 1 item.',
            'parcelas.*.dataVencimento.required'    => ':attribute é obrigatório.',
            'parcelas.*.dataVencimento.date_format' => ':attribute deve estar no formato Y-m-d.',
            'parcelas.*.valor.required'             => ':attribute é obrigatório.',
            'parcelas.*.valor.numeric'              => ':attribute deve ser um número.',
            'parcelas.*.valor.min'                  => ':attribute não pode ser negativo.',
            'condicoesPagamento.required'           => ':attribute é obrigatório.',
            'condicoesPagamento.string'             => ':attribute deve ser uma string.',
            'condicoesPagamento.' . Enum::class     => ':attribute deve ser um dos seguintes valores: ',
            'quantidadeParcela.required'            => ':attribute é obrigatório.',
            'quantidadeParcela.integer'             => ':attribute deve ser um número inteiro.',
            'quantidadeParcela.min'                 => ':attribute não pode ser menor que 1',
            'bancoId.required'                      => ':attribute é obrigatório.',
            'bancoId.integer'                       => ':attribute deve ser um número inteiro.',
            'bancoId.exists'                        => ':attribute informado não existe na tabela de bancos.',
            'valor.required'                        => ':attribute é obrigatório.',
            'valor.numeric'                         => ':attribute deve ser um número.',
            'valor.min'                             => ':attribute não pode ser negativo.',
            'formaPagamentoId.required'             => ':attribute é obrigatório.',
            'formaPagamentoId.integer'              => ':attribute deve ser um número inteiro.',
            'formaPagamentoId.exists'               => ':attribute informado não existe na tabela de formas de pagamento.',

        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
