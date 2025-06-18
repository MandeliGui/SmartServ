<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\CategoriaEntradaSaidaModel;

class CategoriaEntradaSaidaService
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

        return CategoriaEntradaSaidaModel::query()
            ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return CategoriaEntradaSaidaModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return CategoriaEntradaSaidaModel::query()->create([
            'nome'      => $data['nome'],
            'tipo'      => $data['tipo'],
            'descricao' => $data['descricao'],
            'user_id'   => auth()->user()->id,
        ]);
    }

    public function update(array $data, mixed $id)
    {
        $categoria = CategoriaEntradaSaidaModel::query()->where('id', $id);

        $categoria->update([
            'nome'      => $data['nome'],
            'descricao' => $data['descricao']
        ]);

        return $categoria;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $categoria = CategoriaEntradaSaidaModel::query()->where('id', $id);

        $categoria->delete();

        return $categoria;
    }
}
