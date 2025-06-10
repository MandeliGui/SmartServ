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
//        dd($data);

        $ordemServico = OrdemServicoModel::find($id);


        if (!empty($data['materiais'])) {


            $data['materiais'] = array_filter($data['materiais'], function ($material) {
                return isset($material['idMaterial']);
            });
            $ordemServico->materiais()->attach($data['materiais']);


        }
        if (!empty($data['servicos'])) {
            $data['servicos'] = array_filter($data['servicos'], function ($servico) {
                return isset($servico['idServico']);
            });
            $ordemServico->servicos()->attach($data['servicos']);
        }
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

    public function editarMaterial(array $data)
    {
        $ordemServico = OrdemServicoModel::query()
            ->whereHas('materiais', function (Builder $query) use ($data) {
                $query->where('tb_ordem_servico_material.id', $data['id']);
            })
            ->first();


        if ($ordemServico) {


            $materiais = $ordemServico->materiais()
                ->where('tb_ordem_servico_material.id', $data['id'])->first();

            $materiais->pivot->quantidade    = $data['quantidade'];
            $materiais->pivot->valorUnitario = $data['valorUnitario'];
            $materiais->pivot->valorTotal    = $data['valorTotal'];
            $materiais->pivot->save();


            $materiais                = $ordemServico->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $servicos                 = $ordemServico->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });
            $ordemServico->valorTotal = $materiais->sum('valorTotal') + $servicos->sum('valorTotal');
            $ordemServico->save();
            return true;
        }

        return false;
    }

    public function removeMaterial(mixed $id)
    {

        $ordemServico = OrdemServicoModel::query()
            ->whereHas('materiais', function ($query) use ($id) {
                $query->where('tb_ordem_servico_material.id', $id);
            })
            ->first();


        if ($ordemServico) {

            $ordemServico->materiais()
                ->wherePivot('id', $id)
                ->detach();

            $materiais                = $ordemServico->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $servicos                 = $ordemServico->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });
            $ordemServico->valorTotal = $materiais->sum('valorTotal') + $servicos->sum('valorTotal');
            $ordemServico->save();
            return true;
        }

        return false;
    }

    public function editarServico(array $data)
    {
        $ordemServico = OrdemServicoModel::query()
            ->whereHas('servicos', function (Builder $query) use ($data) {
                $query->where('tb_ordem_servico_servico.id', $data['id']);
            })
            ->first();

        if ($ordemServico) {

            $servicos = $ordemServico->servicos()
                ->where('tb_ordem_servico_servico.id', $data['id'])->first();

            $servicos->pivot->quantidade    = $data['quantidade'];
            $servicos->pivot->valorUnitario = $data['valorUnitario'];
            $servicos->pivot->valorTotal    = $data['valorTotal'];
            $servicos->pivot->save();

            $servicos                 = $ordemServico->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });
            $materials                = $ordemServico->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $ordemServico->valorTotal = $servicos->sum('valorTotal') + $materials->sum('valorTotal');
            $ordemServico->save();
            return true;
        }

        return false;
    }

    public function removeServico(mixed $id)
    {
        $ordemServico = OrdemServicoModel::query()
            ->whereHas('servicos', function (Builder $query) use ($id) {
                $query->where('tb_ordem_servico_servico.id', $id);
            })
            ->first();

        if ($ordemServico) {

            $ordemServico->servicos()
                ->wherePivot('id', $id)
                ->detach();

            $servicos = $ordemServico->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });

            $materiais                = $ordemServico->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $ordemServico->valorTotal = $servicos->sum('valorTotal') + $materiais->sum('valorTotal');
            $ordemServico->save();
            return true;
        }

        return false;
    }
}
