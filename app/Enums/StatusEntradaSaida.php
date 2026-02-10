<?php

namespace App\Enums;

enum StatusEntradaSaida: int
{
    case PENDENTE  = 1;
    case PAGO      = 2;
    case CANCELADO = 3;

    public static function label($status): string
    {
        return match ($status) {
            StatusEntradaSaida::PENDENTE->value => 'Pendente',
            StatusEntradaSaida::PAGO->value => 'Pago',
            StatusEntradaSaida::CANCELADO->value => 'Cancelado',
        };
    }

    public static function colors($status): array
    {
        return match ($status) {
            StatusEntradaSaida::PENDENTE->value => ['bg' => 'bg-yellow-100', 'text' => 'text-amber-400'],
            StatusEntradaSaida::PAGO->value => ['bg' => 'bg-green-100', 'text' => 'text-green-400'],
            StatusEntradaSaida::CANCELADO->value => ['bg' => 'bg-red-100', 'text' => 'text-red-400'],
        };
    }
}
