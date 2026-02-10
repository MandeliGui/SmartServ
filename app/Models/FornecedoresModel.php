<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FornecedoresModel extends Model
{
    protected $table = 'tb_fornecedores';

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'atendente',
        'cnpj',
        'telefone',
        'email',
        'id_endereco',
        'user_id',
    ];

    public function endereco()
    {
        return $this->belongsTo(EnderecoModel::class, 'id_endereco', 'id');
    }


}
