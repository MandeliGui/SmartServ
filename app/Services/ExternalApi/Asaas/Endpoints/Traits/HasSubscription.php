<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Traits;

use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Subscriptions;

trait HasSubscription
{
    public function subscription(): Subscriptions
    {
        return new Subscriptions();
    }

}
