<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BancosModel extends Model
{
    protected $table = 'tb_bancos';

    protected $fillable = [
        'nome',
        'saldo_inicial',
        'saldo',
        'removido',
        'user_id',
    ];


    public function entradasSaidas()
    {
        return $this->hasMany(EntradasSaidasModel::class, 'banco_id');
    }
}
