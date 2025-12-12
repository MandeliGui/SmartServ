<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Endpoints;

use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Base\BaseEndpoint;
use App\Services\ExternalApi\Asaas\Endpoints\Request\SubscriptionRequest;
use App\Services\ExternalApi\Asaas\Entities\Subscription;
use Illuminate\Http\Client\ConnectionException;

class Subscriptions extends BaseEndpoint
{
    /**
     * @throws ConnectionException
     */
    public function get($offSet = null, $limit = null, $name = null, $email = null, $cpfCnpj = null, $groupName = null, $deletedOnly = null, $includeDeleted = null, $externalReference = null, $order = null, $sort = null)
    {
        return parent::transform(
            $this->service
                ->api
                ->withQueryParameters([
                    'offset'            => $offSet,
                    'limit'             => $limit,
                    'customer'          => $name,
                    'customerGroupName' => $email,
                    'billingType'       => $cpfCnpj,
                    'status'            => $groupName,
                    'deletedOnly'       => $deletedOnly,
                    'includeDeleted'    => $includeDeleted,
                    'externalReference' => $externalReference,
                    'order'             => $order,
                    'sort'              => $sort,
                ])
                ->get('/subscriptions')
                ->json('data'),
            Subscription::class
        );
    }

    /**
     * @throws ConnectionException
     */
    public function post(array $data)
    {

        $data = SubscriptionRequest::create($data)->validated();


        return parent::transform(
            $this->service
                ->api
                ->post('/subscriptions', $data)
                ->json(),
            Subscription::class
        );

    }
}
