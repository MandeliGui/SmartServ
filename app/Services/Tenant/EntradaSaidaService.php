<?php

namespace App\Services\Tenant;

use App\Enums\TipoEntradaSaida;
use App\Enums\TipoOrdemServico;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\BancosModel;
use App\Models\EntradasSaidasModel;

class EntradaSaidaService
{
    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        return EntradasSaidasModel::query()
            ->when($filters['idBanco'], function ($query, $idBanco) {

                $query->where('banco_id', $idBanco);
            })
            ->orderBy($request->orderBy)
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {

    }

    public function create(array $data)
    {

        $entradaSaida = EntradasSaidasModel::query()->create([
            'tipo'             => $data['tipo'],
            'data_vencimento'  => $data['data_vencimento'],
            'valor_original'   => $data['valor_original'],
            'quantidade_meses' => $data['quantidade_meses'],
            'descricao'        => $data['descricao'],
            'categoria_id'     => $data['categoria_id'],
            'banco_id'         => $data['banco_id'],
            'removido'         => $data['removido'],
            'user_id'          => auth()->user()->id,
        ]);


        return $entradaSaida;
    }
}
