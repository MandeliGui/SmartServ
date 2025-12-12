<?php

namespace App\Services\ExternalApi\Asaas;

use App\Services\ExternalApi\Asaas\Endpoints\Traits\HasCobrancas;
use App\Services\ExternalApi\Asaas\Endpoints\Traits\HasCustomers;
use App\Services\ExternalApi\Asaas\Endpoints\Traits\HasSubscription;
use Illuminate\Http\Client\PendingRequest;

class AsaasService
{
    use HasCobrancas;
    use HasCustomers;
    use HasSubscription;

    public PendingRequest $api;

    public function __construct()
    {
        $baseUrl = \Config::get('app.env') == 'local' ? \Config::get('app.external_api.asaas.local.base_url') : \Config::get('app.external_api.asaas.production.base_url');


        $accessToken = config('app.env') == 'local' ? config('app.external_api.asaas.local.access_token') : config('app.external_api.asaas.production.access_token');

        $this->api = \Http::withHeaders([
            'accept'       => 'application/json',
            'access_token' => $accessToken,
            'content-type' => 'application/json',
        ])->baseUrl($baseUrl);
    }
}
