<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\BancosModel;

class BancoService
{
    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        $request->validate(
            rules: [
                "orderBy" => ["in:nome"],
            ],
            messages: [
                "orderBy.in" => "não e possível ordernar pela coluna :attribute",
            ]
        );

        return BancosModel::query()
            ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return BancosModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return BancosModel::query()->create([
            'nome'          => $data['nome'],
            'saldo_inicial' => $data['saldo_inicial'],
            'saldo'         => $data['saldo_inicial'], // Inicialmente o saldo é igual ao saldo inicial
            'user_id'       => auth()->user()->id,
        ]);
    }

    public function update(array $data, mixed $id)
    {
        $servico = BancosModel::query()->where('id', $id);

        $servico->update([
            'nome' => $data['nome'],
        ]);

        return $servico;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $servicos = BancosModel::query()->where('id', $id);

        $servicos->delete();

        return $servicos;
    }
}
