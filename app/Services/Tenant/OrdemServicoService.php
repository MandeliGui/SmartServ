<?php

namespace App\Services\Tenant;

use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\OrdemServicoModel;
use Illuminate\Database\Eloquent\Builder;

class OrdemServicoService
{
    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        $request->validate(
            rules: [
                "orderBy" => ["in:codigo"],
            ],
            messages: [
                "orderBy.in" => "não e possível ordernar pela coluna :attribute",
            ]
        );

        return OrdemServicoModel::query()
            ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                $query->orderBy($request->orderBy, $request->dir);
            })->when(!empty($request->search), function ($query) use ($request): void {
                $query->search($request->search);
            })
            ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return OrdemServicoModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {

   
        $ordemServico = OrdemServicoModel::query()->create([
            'codigo'       => $data['codigo'],
            'tipo'         => $data['tipo'],
            'dataAbertura' => $data['dataAbertura'],
            'dataEntrega'  => $data['dataEntrega'],
            'status'       => $data['status'],
            'valorTotal'   => $data['valorTotal'],
            'idCliente'    => $data['idCliente'],
            'idTecnico'    => $data['idTecnico'],
            'idAtendente'  => $data['idAtendente'],
        ]);

        if (!empty($data['materiais'])) {
            $ordemServico->materiais()->attach($data['materiais']);
        }
        if (!empty($data['servicos'])) {
            $ordemServico->servicos()->attach($data['servicos']);
        }
        return $ordemServico;

    }

    public function update(array $data, mixed $id)
    {
        $ordemServico = OrdemServicoModel::query()->where('id', $id);

        $ordemServico->update([
            'tipo'         => $data['tipo'],
            'dataAbertura' => $data['dataAbertura'],
            'dataEntrega'  => $data['dataEntrega'],
            'status'       => $data['status'],
            'valorTotal'   => $data['valorTotal'],
            'idCliente'    => $data['idCliente'],
            'idTecnico'    => $data['idTecnico'],
            'idAtendente'  => $data['idAtendente'],
        ]);

        return $ordemServico;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $servicos = OrdemServicoModel::query()->where('id', $id);

        $servicos->delete();

        return $servicos;
    }
}
