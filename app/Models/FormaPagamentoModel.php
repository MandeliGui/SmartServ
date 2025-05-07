<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamentoModel extends Model
{
    protected $table      = 'tb_formas_pagamento';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'nome',
        'descricao',
        'user_id',
        'removido',
    ];
}
