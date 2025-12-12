<?php

namespace App\Services\ExternalApi\Asaas\Facades;

use App\Services\ExternalApi\Asaas\AsaasService;
use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Cobrancas;
use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Customers;
use Illuminate\Support\Facades\Facade;

class Asaas extends Facade
{

    /**
     * @method static Cobrancas cobrancas()
     * @method static Customers customers()
     *
     * */
    protected static function getFacadeAccessor(): string
    {
        return AsaasService::class;
    }
}
