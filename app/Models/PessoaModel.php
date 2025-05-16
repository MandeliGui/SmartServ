<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function endereco(): BelongsTo
    {
        return $this->belongsTo(EnderecoModel::class, 'idEndereco', 'id');
    }

    public function cliente(): HasOne|PessoaModel
    {
        return $this->hasOne(ClienteModel::class, 'idCliente', 'id');
    }

    public function tecnico(): HasOne|PessoaModel
    {
        return $this->hasOne(TecnicoModel::class, 'idTecnico', 'id');
    }

    public function atendente(): HasOne|PessoaModel
    {
        return $this->hasOne(AtendenteModel::class, 'idAtendente', 'id');
    }
}
