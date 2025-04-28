<?php

declare(strict_types = 1);

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\GrupoClienteModel;

class GrupoClientesService
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

        return GrupoClienteModel::query()
            ->when(! is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return GrupoClienteModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return GrupoClienteModel::query()->create([

            'nome'    => $data['nome'],
            'user_id' => auth()->user()->id,
        ]);
    }

    public function update(array $data, mixed $id)
    {
        $grupo = GrupoClienteModel::query()->where('id', $id);

        $grupo->update([

            'nome' => $data['nome'],

        ]);

        return $grupo;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $grupo = GrupoClienteModel::query()->where('id', $id);

        $grupo->delete();

        return $grupo;
    }
}
