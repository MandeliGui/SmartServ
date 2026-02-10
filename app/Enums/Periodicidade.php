<?php

namespace App\Enums;

enum Periodicidade: string
{
    case MENSAL        = 'MENSAL';
    case BIMESTRAL     = 'BIMESTRAL';
    case TRIMESTRAL    = 'TRIMESTRAL';
    case QUADRIMESTRAL = 'QUADRIMESTRAL';
    case SEMESTRAL     = 'SEMESTRAL';
    case ANUAL         = 'ANUAL';
}
