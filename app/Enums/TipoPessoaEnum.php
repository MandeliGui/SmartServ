<?php

namespace App\Enums;

enum TipoPessoaEnum: string
{
    case PESSOA_JURIDICA = 'PJ';
    case PESSOA_FISICA   = 'PF';
}
