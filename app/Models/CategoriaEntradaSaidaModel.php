<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEntradaSaidaModel extends BaseModel
{
    protected $table = 'tb_categoria_entrada_saida';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';


    protected $fillable = [
        'id',
        'nome',
        'tipo',
        'descricao',
        'removido',
        'user_id',
    ];

    protected $casts = [
        'tipo' => 'integer',
    ];

}
