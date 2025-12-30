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
        ds()->queriesOn();
        return $query->where(function ($query) use ($search) {
            $query->where('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('tipo', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('valorTotal', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente.pessoa', function ($q2) use ($search) {
                      $q2->where('nomeFantasia', 'LIKE', "%{$search}%")
                      ->orWhere('nomeRazaoSocial', 'LIKE', "%{$search}%")
                      ->orWhere('telefone', 'LIKE', "%{$search}%");
                  });
        });
        ds()->queriesOff();
    }

    public function materiais()
    {
        return $this->belongsToMany(MaterialModel::class, 'tb_ordem_servico_material', 'idOrdemServico', 'idMaterial')
                    ->withPivot('id', 'idMaterial', 'quantidade', 'descricao', 'valorUnitario', 'valorTotal')
                    ->withTimestamps();
    }

    public function servicos()
    {
        return $this->belongsToMany(ServicosModel::class, 'tb_ordem_servico_servico', 'idOrdemServico', 'idServico')
                    ->withPivot('id', 'idServico', 'quantidade', 'descricao', 'valorUnitario', 'valorTotal')
                    ->withTimestamps();
    }

    public function cliente()
    {
        return $this->belongsTo(ClienteModel::class, 'idCliente', 'idCliente');
    }

    public function tecnico()
    {
        return $this->belongsTo(UsuarioModel::class, 'idTecnico', 'id');
    }

    public function atendente()
    {
        return $this->belongsTo(UsuarioModel::class, 'idAtendente', 'id');
    }

    public function entradasSaidas()
    {
        return $this->hasMany(EntradasSaidasModel::class, 'ordem_servico_id', 'id');
    }
}
