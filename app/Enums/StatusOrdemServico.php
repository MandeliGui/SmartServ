<?php

namespace App\Enums;

enum StatusOrdemServico: string
{
    case PENDENTE     = 'Pendente';
    case EM_ANDAMENTO = 'EmAndamento';
    case FINALIZADO   = 'Finalizado';
    case CANCELADO   = 'Cancelado';
}
