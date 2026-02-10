<?php

namespace App\Enums\Tenant;

enum PeriodicidadeEnum: string
{
    case UNICA      = 'UNICA';
    case MENSAL     = 'MENSAL';
    case BIMESTRAL  = 'BIMESTRAL';
    case TRIMESTRAL = 'TRIMESTRAL';
    case SEMESTRAL  = 'SEMESTRAL';
    case ANUAL      = 'ANUAL';

    public static function obterNumero($value)
    {
        return match ($value) {
            PeriodicidadeEnum::UNICA->value => 1,
            PeriodicidadeEnum::MENSAL->value => 1,
            PeriodicidadeEnum::BIMESTRAL->value => 2,
            PeriodicidadeEnum::TRIMESTRAL->value => 3,
            PeriodicidadeEnum::SEMESTRAL->value => 6,
            PeriodicidadeEnum::ANUAL->value => 12,
            default => 1,
        };
    }
}
