<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Traits;

use App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Customers;

trait HasCustomers
{
    public function customers(): Customers
    {
        return new Customers();
    }
}
