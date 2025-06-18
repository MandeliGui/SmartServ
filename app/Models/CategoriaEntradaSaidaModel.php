<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEntradaSaidaModel extends Model
{
    protected $table = 'tb_categoria_entrada_saida';

    protected $fillable = [
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
