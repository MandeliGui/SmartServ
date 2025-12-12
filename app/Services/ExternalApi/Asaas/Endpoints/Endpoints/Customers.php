<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Endpoints;

use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Base\BaseEndpoint;
use App\Services\ExternalApi\Asaas\Endpoints\Request\CustomerRequest;
use App\Services\ExternalApi\Asaas\Entities\Customer;
use App\Services\ExternalApi\Asaas\Facades\Asaas;
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

    /**
     * @throws ConnectionException
     */
    public function post(array $data)
    {


        $data           = CustomerRequest::create($data, ['name' => 'Nome', 'cpfCnpj' => 'Cpf ou Cnpj'])->validated();
        $existeCustomer = Asaas::customers()->get(cpfCnpj: $data['cpfCnpj']);

        if ($existeCustomer->count() > 0) {
            return $existeCustomer->first();
        }


        return parent::transform(
            $this->service
                ->api
                ->post('/customers', $data)
                ->json(),
            Customer::class
        );

    }
}
