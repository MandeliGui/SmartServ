<?php

declare(strict_types = 1);

namespace App\Models;

class GrupoClienteModel extends BaseModel
{
    protected $table = 'tb_grupo_cliente';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'removido',
        'user_id',
    ];

    public function clientes()
    {
        return $this->hasMany(ClienteModel::class, 'idGrupo', 'id');
    }
}
