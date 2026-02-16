<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratosModel extends Model
{
    protected $table = 'tb_contratos';

    protected $fillable = [
        "id_cliente",
        "periodicidade",
        "valor",
        "status",
        "data_inicio_contrato",
        "removido",
    ];


    public function materiais()
    {
        return $this->belongsToMany(MaterialModel::class, 'tb_contrato_materiais', 'idContrato', 'idMaterial')
                    ->withPivot('id', 'idMaterial', 'quantidade', 'descricao', 'valorUnitario', 'valorTotal')
                    ->withTimestamps();
    }

    public function servicos()
    {
        return $this->belongsToMany(ServicosModel::class, 'tb_contrato_servicos', 'idContrato', 'idServico')
                    ->withPivot('id', 'idServico', 'quantidade', 'descricao', 'valorUnitario', 'valorTotal')
                    ->withTimestamps();
    }

    public function cliente()
    {
        return $this->belongsTo(ClienteModel::class, 'id_cliente', 'idCliente');
    }

    public function ordemServico()
    {
        return $this->hasMany(OrdemServicoModel::class, 'contratoId', 'id');
    }
}
