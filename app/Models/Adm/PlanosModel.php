<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Model;

class PlanosModel extends Model
{
    protected $table = 'tb_planos';

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
    ];

    protected $casts = [
        'valor' => 'integer',
    ];

    public function getValorAttribute($value)
    {
        return $value / 100;
    }

    public function user()
    {
        return $this->hasOne(UserPlano::class);
    }
}
