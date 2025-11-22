<?php

namespace App\Services\ExternalApi\Asaas\Endpoints;

trait HasCustomers
{
    public function customers(): Customers
    {
        return new Customers();
    }
}
