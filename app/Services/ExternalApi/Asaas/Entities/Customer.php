<?php

namespace App\Services\ExternalApi\Asaas\Entities;

class Customer
{


    public ?string $object;
    public ?string $id;
    public ?string $dateCreated;
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?string $mobilePhone;
    public ?string $address;
    public ?string $addressNumber;
    public ?string $complement;
    public ?string $province;
    public ?int    $city;
    public ?string $cityName;
    public ?string $state;
    public ?string $country;
    public ?string $postalCode;
    public ?string $cpfCnpj;
    public ?string $personType;
    public ?bool   $deleted;
    public ?string $additionalEmails;
    public ?string $externalReference;
    public ?bool   $notificationDisabled;
    public ?string $observations;
    public ?bool   $foreignCustomer;

    public function __construct(array $data)
    {
        $this->object               = data_get($data, 'object');
        $this->id                   = data_get($data, 'id');
        $this->dateCreated          = data_get($data, 'dateCreated');
        $this->name                 = data_get($data, 'name');
        $this->email                = data_get($data, 'email');
        $this->phone                = data_get($data, 'phone');
        $this->mobilePhone          = data_get($data, 'mobilePhone');
        $this->address              = data_get($data, 'address');
        $this->addressNumber        = data_get($data, 'addressNumber');
        $this->complement           = data_get($data, 'complement');
        $this->province             = data_get($data, 'province');
        $this->city                 = data_get($data, 'city');
        $this->cityName             = data_get($data, 'cityName');
        $this->state                = data_get($data, 'state');
        $this->country              = data_get($data, 'country');
        $this->postalCode           = data_get($data, 'postalCode');
        $this->cpfCnpj              = data_get($data, 'cpfCnpj');
        $this->personType           = data_get($data, 'personType');
        $this->deleted              = data_get($data, 'deleted');
        $this->additionalEmails     = data_get($data, 'additionalEmails');
        $this->externalReference    = data_get($data, 'externalReference');
        $this->notificationDisabled = data_get($data, 'notificationDisabled');
        $this->observations         = data_get($data, 'observations');
        $this->foreignCustomer      = data_get($data, 'foreignCustomer');
    }
}
