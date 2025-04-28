<?php

declare(strict_types = 1);

namespace App\Models;

class TecnicoModel extends BaseModel
{
    protected $table = 'tb_tecnicos';

    protected $fillable = [
        'idTecnico',
        'user_id',
        'removido',
    ];

    protected $primaryKey = 'idTecnico';

    public function pessoa()
    {
        return $this->belongsTo(PessoaModel::class, 'idTecnico', 'id');
    }
}
