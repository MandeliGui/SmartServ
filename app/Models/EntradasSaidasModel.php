<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntradasSaidasModel extends BaseModel
{
    protected $table = 'tb_entradas_saidas';

    protected $fillable = [
        'tipo',
        'data_vencimento',
        'data_pagamento',
        'valor_original',
        'valor_pago',
        'quantidade_meses',
        'descricao',
        'categoria_id',
        'forma_pagamento_id',
        'banco_id',
        'removido',
        'user_id',
    ];

    public $timestamps = true;

    public function categoria()
    {
        return $this->belongsTo(CategoriaEntradaSaidaModel::class, 'categoria_id');
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamentoModel::class, 'forma_pagamento_id');
    }

    public function banco()
    {
        return $this->belongsTo(BancosModel::class, 'banco_id');
    }

}
