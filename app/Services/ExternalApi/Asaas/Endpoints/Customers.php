<?php

namespace App\Services\ExternalApi\Asaas\Endpoints;

use App\Services\ExternalApi\Asaas\Entities\Customer;
use Illuminate\Http\Client\ConnectionException;

class Customers extends BaseEndpoint
{


    /**
     * @throws ConnectionException
     */
    public function get($offSet = null, $limit = null, $name = null, $email = null, $cpfCnpj = null, $groupName = null, $externalReference = null)
    {
        return parent::transform(
            $this->service
                ->api
                ->withQueryParameters([
                    'offset'            => $offSet,
                    'limit'             => $limit,
                    'name'              => $name,
                    'email'             => $email,
                    'cpfCnpj'           => $cpfCnpj,
                    'groupName'         => $groupName,
                    'externalReference' => $externalReference,
                ])
                ->get('/customers')
                ->json('data'),
            Customer::class
        );
    }
}
