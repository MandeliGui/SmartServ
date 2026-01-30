<?php

declare(strict_types=1);

namespace App\Models;

class EnderecoModel extends BaseModel
{
    protected $table = 'tb_enderecos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        "user_id",
    ];

    public function pessoas()
    {
        return $this->hasMany(PessoaModel::class, 'idEndereco', 'id');
    }
}
