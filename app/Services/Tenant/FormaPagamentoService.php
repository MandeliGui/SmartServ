<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\FormaPagamentoModel;

class FormaPagamentoService
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

        return FormaPagamentoModel::query()
            ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return FormaPagamentoModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return FormaPagamentoModel::query()->create([
            'nome'      => $data['nome'],
            'descricao' => $data['descricao'],
            'user_id'   => auth()->user()->id,
        ]);
    }

    public function update(array $data, mixed $id)
    {
        $servico = FormaPagamentoModel::query()->where('id', $id);

        $servico->update([
            'nome'      => $data['nome'],
            'descricao' => $data['descricao']
        ]);

        return $servico;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $servicos = FormaPagamentoModel::query()->where('id', $id);

        $servicos->delete();

        return $servicos;
    }
}
