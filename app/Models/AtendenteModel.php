<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtendenteModel extends BaseModel
{
    protected $table = 'tb_atendentes';

    protected $fillable = [
        'idAtendente',
        'user_id',
        'removido',
    ];

    protected $primaryKey = 'idAtendente';

    public function pessoa()
    {
        return $this->belongsTo(PessoaModel::class, 'idAtendente', 'id');
    }
}
