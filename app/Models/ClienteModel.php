<?php

declare(strict_types=1);

namespace App\Models;

class ClienteModel extends BaseModel
{
    protected $table = 'tb_cliente';

    protected $primaryKey = 'idCliente';

    protected $fillable = [
        'idCliente',
        'idGrupo',
        'user_id',
    ];

    public function grupo()
    {
        return $this->belongsTo(GrupoClienteModel::class, 'idGrupo', 'id');
    }

    public function pessoa()
    {
        return $this->belongsTo(PessoaModel::class, 'idCliente', 'id');
    }

    public function ordemServico()
    {
        return $this->hasMany(OrdemServicoModel::class, 'idCliente', 'idCliente');
    }
}
