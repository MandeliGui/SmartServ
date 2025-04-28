<?php

declare(strict_types = 1);

namespace App\Models;

class ServicosModel extends BaseModel
{
    protected $table = 'tb_servicos';

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'valor',
        'user_id',
    ];

    protected $primaryKey = 'id';
}
