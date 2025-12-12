<?php

namespace App\Services\ExternalApi\Asaas\Endpoints\Endpoints\Base;

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
        $data = empty($json) ? [] : (is_array($json) && array_keys($json) !== range(0, count($json) - 1) ? [$json] : $json);

        return collect($data)->map(fn($item) => new $entity($item));
    }

}
