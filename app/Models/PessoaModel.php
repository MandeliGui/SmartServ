<?php

declare(strict_types = 1);

namespace App\Models;

class PessoaModel extends BaseModel
{
    //    protected $connection = 'tenant';
    protected $table = 'tb_pessoas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nomeRazaoSocial',
        'nomeFantasia',
        'telefone',
        'cpfCnpj',
        'email',
        'dataNascimento',
        'tipoPessoa',
        'idEndereco',
        'user_id',
    ];

    public function endereco()
    {
        return $this->belongsTo(EnderecoModel::class, 'idEndereco', 'id');
    }

    public function cliente()
    {
        return $this->hasOne(ClienteModel::class, 'idCliente', 'id');
    }

    public function tecnico()
    {
        return $this->hasOne(TecnicoModel::class, 'idTecnico', 'id');
    }
}
