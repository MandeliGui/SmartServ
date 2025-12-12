<?php

namespace App\Services\ExternalApi\Asaas\Entities;

class Subscription
{

//    "object": "subscription",
//      "id": "sub_VXJBYgP2u0eO",
//      "dateCreated": "2017-03-17",
//      "customer": "cus_0T1mdomVMi39",
//      "paymentLink": null,
//      "billingType": "BOLETO",
//      "cycle": "MONTHLY",
//      "value": 19.9,
//      "nextDueDate": "2017-06-15",
//      "endDate": "2018-06-15",
//      "description": "Assinatura Plano Pró",
//      "status": "ACTIVE",
//      "discount": {
//        "value": 10,
//        "dueDateLimitDays": 0,
//        "type": "PERCENTAGE"
//      },
//      "fine": {
//        "value": 1
//      },
//      "interest": {
//        "value": 2
//      },
//      "deleted": false,
//      "maxPayments": 12,
//      "externalReference": null,
//      "checkoutSession": "356eb0c4-9eb7-4b7f-b2be-d9479af1d29f",
//      "split": [
//        {
//          "walletId": "7bafd95a-e783-4a62-9be1-23999af742c6",
//          "fixedValue": 20.32,
//          "percentualValue": null,
//          "externalReference": null,
//          "description": null,
//          "status": "ACTIVE",
//          "disabledReason": "WALLET_UNABLE_TO_RECEIVE"
//        }


    public $id;
    public $dateCreated;
    public $customer;
    public $paymentLink;
    public $billingType;
    public $cycle;
    public $value;
    public $nextDueDate;
    public $endDate;
    public $description;
    public $status;
    public $discount;
    public $fine;
    public $interest;
    public $deleted;
    public $maxPayments;
    public $externalReference;
    public $checkoutSession;
    public $split;


    public function __construct(array $data)
    {
        $this->id                = data_get($data, 'id');
        $this->dateCreated       = data_get($data, 'dateCreated');
        $this->customer          = data_get($data, 'customer');
        $this->paymentLink       = data_get($data, 'paymentLink');
        $this->billingType       = data_get($data, 'billingType');
        $this->cycle             = data_get($data, 'cycle');
        $this->value             = data_get($data, 'value');
        $this->nextDueDate       = data_get($data, 'nextDueDate');
        $this->endDate           = data_get($data, 'endDate');
        $this->description       = data_get($data, 'description');
        $this->status            = data_get($data, 'status');
        $this->discount          = data_get($data, 'discount');
        $this->fine              = data_get($data, 'fine');
        $this->interest          = data_get($data, 'interest');
        $this->deleted           = data_get($data, 'deleted');
        $this->maxPayments       = data_get($data, 'maxPayments');
        $this->externalReference = data_get($data, 'externalReference');
        $this->checkoutSession   = data_get($data, 'checkoutSession');
        $this->split             = data_get($data, 'split');

    }
}
