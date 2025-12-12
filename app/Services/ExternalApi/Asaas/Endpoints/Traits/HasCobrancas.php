<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Traits;

use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Cobrancas;

trait HasCobrancas
{
    public function payment(): Cobrancas
    {
        return new Cobrancas();
    }
}
