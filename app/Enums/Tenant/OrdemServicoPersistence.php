<?php

namespace App\Enums\Tenant;

enum OrdemServicoPersistence: string
{
    case REMOVER_MATERIAL = "REMOVER_MATERIAL";
    case EDITAR_MATERIAL  = "EDITAR_MATERIAL";
    case REMOVER_SERVICO  = "REMOVER_SERVICO";
    case EDITAR_SERVICO   = "EDITAR_SERVICO";
}
