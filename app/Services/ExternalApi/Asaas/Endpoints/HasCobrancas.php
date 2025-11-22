<?php

namespace App\Services\ExternalApi\Asaas\Endpoints;

trait HasCobrancas
{
    public function cobrancas(): Cobrancas
    {
        return new Cobrancas();
    }
}
