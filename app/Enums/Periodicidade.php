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

    public static function getPeriodicidadeEmNumero($periodicidade): int
    {
        return match ($periodicidade) {
            self::MENSAL->value => 1,
            self::BIMESTRAL->value => 2,
            self::TRIMESTRAL->value => 3,
            self::QUADRIMESTRAL->value => 4,
            self::SEMESTRAL->value => 6,
            self::ANUAL->value => 12,
            default => throw new \InvalidArgumentException("Periodicidade inválida: $periodicidade"),
        };
    }
}
