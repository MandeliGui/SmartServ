<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\MaterialModel;

class MateriaisService
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

        return MaterialModel::query()
            ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return MaterialModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return MaterialModel::query()->create([
            'codigo'    => $data['codigo'],
            'nome'      => $data['nome'],
            'descricao' => $data['descricao'],
            'unidade'   => $data['unidade'],
            'valor'     => $data['valor'],
            'user_id'   => auth()->user()->id,
        ]);
    }

    public function update(array $data, mixed $id)
    {
        $servico = MaterialModel::query()->where('id', $id);

        $servico->update([
            'codigo'    => $data['codigo'],
            'nome'      => $data['nome'],
            'descricao' => $data['descricao'],
            'unidade'   => $data['unidade'],
            'valor'     => $data['valor'],
        ]);

        return $servico;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $servicos = MaterialModel::query()->where('id', $id);

        $servicos->delete();

        return $servicos;
    }
}
