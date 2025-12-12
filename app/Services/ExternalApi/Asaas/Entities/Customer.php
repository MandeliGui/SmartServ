<?php

namespace App\Services\ExternalApi\Asaas\Entities;

class Customer
{


    public ?string $id;
    public ?string $name;
    public ?string $cpfCnpj;


    public function __construct(array $data)
    {

        $this->id      = data_get($data, 'id');
        $this->name    = data_get($data, 'name');
        $this->cpfCnpj = data_get($data, 'cpfCnpj');

    }
}
