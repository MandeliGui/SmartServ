<?php

namespace App\Enums;

enum FormaPagamentoContratacaoEnum: string
{
    case CREDIT_CARD = 'CREDIT_CARD';
    case BOLETO      = 'BOLETO';
}
