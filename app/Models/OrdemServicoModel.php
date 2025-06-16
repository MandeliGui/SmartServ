<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemServicoModel extends BaseModel
{
    protected $table      = 'tb_ordem_servico';
    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo',
        'tipo',
        'dataAbertura',
        'dataEntrega',
        'status',
        'valorTotal',
        'idCliente',
        'idTecnico',
        'idAtendente',
        'user_id',
        'removido'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('codigo', 'LIKE', "%{$search}%")
                ->orWhere('tipo', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('valorTotal', 'LIKE', "%{$search}%")
                ->orWhereHas('cliente', function ($query) use ($search) {
                    $query->pessoa->where('nome', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('tecnico', function ($query) use ($search) {
                    $query->pessoa->where('nome', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('atendente', function ($query) use ($search) {
                    $query->pessoa->where('nome', 'LIKE', "%{$search}%");
                });
        });
    }

    public function materiais()
    {
        return $this->belongsToMany(MaterialModel::class, 'tb_ordem_servico_material', 'idOrdemServico', 'idMaterial')
            ->withPivot('id','idMaterial', 'quantidade', 'valorUnitario', 'valorTotal')
            ->withTimestamps();
    }

    public function servicos()
    {
        return $this->belongsToMany(ServicosModel::class, 'tb_ordem_servico_servico', 'idOrdemServico', 'idServico')
            ->withPivot('id','idServico', 'quantidade', 'valorUnitario', 'valorTotal')
            ->withTimestamps();
    }

    public function cliente()
    {
        return $this->belongsTo(ClienteModel::class, 'idCliente', 'idCliente');
    }
}
