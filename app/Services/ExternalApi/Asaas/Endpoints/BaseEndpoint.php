<?php

namespace App\Services\ExternalApi\Asaas\Endpoints;

use App\Services\ExternalApi\Asaas\AsaasService;
use Illuminate\Support\Collection;

class BaseEndpoint
{
    protected AsaasService $service;

    public function __construct()
    {
        $this->service = new AsaasService();
    }

    protected function transform(mixed $json, string $entity): Collection
    {


        return collect($json)->map(fn($item) => new $entity($item));
    }
}
