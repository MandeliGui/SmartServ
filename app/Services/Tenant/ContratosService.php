<?php

namespace App\Services\Tenant;

use App\Enums\StatusContrato;
use App\Http\Requests\Tenant\FilterPaginateRequest;
use App\Models\ContratosModel;
use Illuminate\Database\Eloquent\Builder;

class ContratosService
{
    public function findAll(FilterPaginateRequest $request, array $filters = [])
    {
        $request->validate(
            rules: [
                "orderBy" => ["in:id"],
            ],
            messages: [
                "orderBy.in" => "não e possível ordernar pela coluna :attribute",
            ]
        );


        return ContratosModel::query()
                             ->when(!is_null($request->orderBy), function ($query) use ($request): void {
                                 $query->orderBy($request->orderBy, $request->dir);
                             })
                             ->paginate(perPage: $request->limit, page: $request->offset);
    }

    public function findOne(mixed $id)
    {
        return ContratosModel::query()->where('id', $id)->first();
    }

    public function create(array $data)
    {
        $valor = 0;
        if (!empty($data['materiais'])) {
            foreach ($data['materiais'] as $material) {
                $valor += $material['valorTotal'];
            }
        }

        if (!empty($data['servicos'])) {
            foreach ($data['servicos'] as $servico) {
                $valor += $servico['valorTotal'];
            }
        }


        $contrato = ContratosModel::query()->create([
            'periodicidade' => $data['periodicidade'],
            'valor'         => $valor,
            'status'        => StatusContrato::ATIVO->value,
            'id_cliente'    => $data['idCliente'],
        ]);

        if (!empty($data['materiais'])) {
            $contrato->materiais()->attach($data['materiais']);
        }
        if (!empty($data['servicos'])) {
            $contrato->servicos()->attach($data['servicos']);
        }
        return $contrato;

    }

    public function update(array $data, mixed $id)
    {

        $contrato = ContratosModel::find($id);

        $valor = 0;
        if (!empty($data['materiais'])) {

            foreach ($data['materiais'] as $material) {
                $valor += $material['valorTotal'];
            }


            $data['materiais'] = array_filter($data['materiais'], function ($material) {
                return isset($material['idMaterial']);
            });
            $contrato->materiais()->attach($data['materiais']);


        }
        if (!empty($data['servicos'])) {

            foreach ($data['servicos'] as $servico) {
                $valor += $servico['valorTotal'];
            }
            $data['servicos'] = array_filter($data['servicos'], function ($servico) {
                return isset($servico['idServico']);
            });
            $contrato->servicos()->attach($data['servicos']);
        }
        $contrato->update([
            'periodicidade' => $data['periodicidade'],
            'valor'         => $valor,
            'id_cliente'    => $data['idCliente'],
        ]);

        return $contrato;
    }

    public function remove(mixed $id)
    {
        //        TODO: Implementar remoção lógica
        $contrato = ContratosModel::query()->find($id)->first();

        $contrato->servicos()->detach();
        $contrato->materiais()->detach();


        $contrato->delete();

        return $contrato;
    }

    public function editarMaterial(array $data)
    {
        $contrato = ContratosModel::query()
                                  ->whereHas('materiais', function (Builder $query) use ($data) {
                                      $query->where('tb_contrato_materiais.id', $data['id']);
                                  })
                                  ->first();


        if ($contrato) {


            $materiais = $contrato->materiais()
                                  ->where('tb_contrato_materiais.id', $data['id'])->first();

            $materiais->pivot->quantidade    = $data['quantidade'];
            $materiais->pivot->valorUnitario = $data['valorUnitario'];
            $materiais->pivot->valorTotal    = $data['valorTotal'];
            $materiais->pivot->save();


            $materiais       = $contrato->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $servicos        = $contrato->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });
            $contrato->valor = $materiais->sum('valorTotal') + $servicos->sum('valorTotal');
            $contrato->save();
            return true;
        }

        return false;
    }

    public function removeMaterial(mixed $id)
    {

        $contrato = ContratosModel::query()
                                  ->whereHas('materiais', function ($query) use ($id) {
                                      $query->where('tb_contrato_materiais.id', $id);
                                  })
                                  ->first();


        if ($contrato) {

            $contrato->materiais()
                     ->wherePivot('id', $id)
                     ->detach();

            $materiais            = $contrato->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $servicos             = $contrato->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });
            $contrato->valor = $materiais->sum('valorTotal') + $servicos->sum('valorTotal');
            $contrato->save();
            return true;
        }

        return false;
    }

    public function editarServico(array $data)
    {
        $contrato = ContratosModel::query()
                                  ->whereHas('servicos', function (Builder $query) use ($data) {
                                      $query->where('tb_contrato_servicos.id', $data['id']);
                                  })
                                  ->first();

        if ($contrato) {

            $servicos = $contrato->servicos()
                                 ->where('tb_contrato_servicos.id', $data['id'])->first();

            $servicos->pivot->quantidade    = $data['quantidade'];
            $servicos->pivot->valorUnitario = $data['valorUnitario'];
            $servicos->pivot->valorTotal    = $data['valorTotal'];
            $servicos->pivot->save();

            $servicos             = $contrato->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });
            $materials            = $contrato->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $contrato->valorTotal = $servicos->sum('valorTotal') + $materials->sum('valorTotal');
            $contrato->save();
            return true;
        }

        return false;
    }

    public function removeServico(mixed $id)
    {
        $contrato = ContratosModel::query()
                                  ->whereHas('servicos', function (Builder $query) use ($id) {
                                      $query->where('tb_contrato_servicos.id', $id);
                                  })
                                  ->first();

        if ($contrato) {

            $contrato->servicos()
                     ->wherePivot('id', $id)
                     ->detach();

            $servicos = $contrato->servicos()->get()->map(function ($servico) {
                return $servico->pivot;
            });

            $materiais            = $contrato->materiais()->get()->map(function ($material) {
                return $material->pivot;
            });
            $contrato->valorTotal = $servicos->sum('valorTotal') + $materiais->sum('valorTotal');
            $contrato->save();
            return true;
        }

        return false;
    }


}
