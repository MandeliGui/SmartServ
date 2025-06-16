<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialModel extends BaseModel
{
    protected $table    = 'tb_materiais';
    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'unidade',
        'valor',
        'user_id'
    ];
}
