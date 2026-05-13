<?php

namespace App\Services\Tenant;

use App\Enums\StatusEntradaSaida;
use App\Enums\Tenant\PeriodicidadeEnum;
use App\Enums\TipoEntradaSaida;
use App\Enums\TipoOrdemServico;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\BancosModel;
use App\Models\EntradasSaidasModel;
use Carbon\Carbon;

class EntradaSaidaService
{
    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        return EntradasSaidasModel::query()
                                  ->when($filters['idBanco'], function ($query, $idBanco) {

                                      $query->where('banco_id', $idBanco);
                                  })
                                  ->orderBy($request->orderBy)
                                  ->orderBy('id')
                                  ->get();
    }

    public function findOne(mixed $id)
    {
        return EntradasSaidasModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
//        dd($data);

        $quantidadeMeses = $data['quantidade_meses'] ?? 1;
        $dataVencimento  = Carbon::parse($data['data_vencimento']);

        for ($i = 1; $i <= $quantidadeMeses; $i++) {

            $entradaSaida = EntradasSaidasModel::query()->create([
                'tipo'               => $data['tipo'],
                'data_vencimento'    => $dataVencimento->format('Y-m-d'),
                'valor_original'     => $data['valor_original'],
                'quantidade_meses'   => $data['quantidade_meses'],
                'data_pagamento'     => $data['data_pagamento'],
                'valor_pago'         => $data['valor_pago'],
                'forma_pagamento_id' => $data['forma_pagamento_id'],
                'descricao'          => $data['descricao'],
                'categoria_id'       => $data['categoria_id'],
                'banco_id'           => $data['banco_id'],
                'id_fornecedor'      => $data['id_fornecedor'],
                'removido'           => $data['removido'],
                'user_id'            => auth()->user()->id,
            ]);

            $dataVencimento = $dataVencimento->addMonthsNoOverflow(PeriodicidadeEnum::obterNumero($data['periodicidade'] ?? PeriodicidadeEnum::UNICA->value));
        }


//        return $entradaSaida;
    }


    public function remove(mixed $id)
    {
        $servico = EntradasSaidasModel::query()->where('id', $id);

        $servico->delete();

        return $servico;
    }

    public function update(array $data, mixed $id)
    {
        $servico = EntradasSaidasModel::query()->where('id', $id);

        $servico->update([
            'tipo'               => $data['tipo'],
            'data_vencimento'    => $data['data_vencimento'],
            'valor_original'     => $data['valor_original'],
            'data_pagamento'     => $data['data_pagamento'],
            'valor_pago'         => $data['valor_pago'],
            'forma_pagamento_id' => $data['forma_pagamento_id'],
            'descricao'          => $data['descricao'],
            'categoria_id'       => $data['categoria_id'],
            'banco_id'           => $data['banco_id'],
            'id_fornecedor'      => $data['id_fornecedor'],
        ]);

        return $servico;
    }

    public function darBaixa(array $data)
    {
        $servico = EntradasSaidasModel::query()->where('id', $data['id']);

        $servico->update([
            'data_pagamento'     => $data['data_pagamento'],
            'status'             => StatusEntradaSaida::PAGO->value,
            'valor_pago'         => $data['valor_pago'],
            'forma_pagamento_id' => $data['forma_pagamento_id'],
            'banco_id'           => $data['banco_id'],
        ]);

        return $servico;
    }

    public function desfazerBaixa(mixed $id)
    {
        $servico = EntradasSaidasModel::query()->where('id', $id)->first();


        $servico->update([
            'status'             => StatusEntradaSaida::PENDENTE->value,
            'data_pagamento'     => null,
            'valor_pago'         => null,
            'forma_pagamento_id' => null,
        ]);

        return $servico;
    }

}
