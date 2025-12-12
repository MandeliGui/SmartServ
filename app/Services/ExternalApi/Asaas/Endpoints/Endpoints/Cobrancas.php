<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Endpoints;

use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Base\BaseEndpoint;
use App\Services\ExternalApi\Asaas\Entities\Cobranca;
use Illuminate\Http\Client\ConnectionException;

class Cobrancas extends BaseEndpoint
{
    /**
     * @throws ConnectionException
     */
    public function get()
    {

        return parent::transform(
            $this->service
                ->api
                ->get('/payments')
                ->json('data'),
            Cobranca::class
        );
    }


}
