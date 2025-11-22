<?php

namespace App\Services\ExternalApi\Asaas\Entities;

class Cobranca
{
    public ?string $object;
    public ?string $id;
    public ?string $dateCreated;
    public ?string $customer;
    public ?string $subscription;
    public ?string $installment;
    public ?string $checkoutSession;
    public ?string $paymentLink;
    public ?float  $value;
    public ?float  $netValue;
    public ?float  $originalValue;
    public ?float  $interestValue;
    public ?string $description;
    public ?string $billingType;
    public ?array  $creditCard;
    public ?bool   $canBePaidAfterDueDate;
    public ?array  $pixTransaction;
    public ?string $pixQrCodeId;
    public ?string $status;
    public ?string $dueDate;
    public ?string $originalDueDate;
    public ?string $paymentDate;
    public ?string $clientPaymentDate;
    public ?int    $installmentNumber;
    public ?string $invoiceUrl;
    public ?string $invoiceNumber;
    public ?string $externalReference;
    public ?bool   $deleted;
    public ?bool   $anticipated;
    public ?bool   $anticipable;
    public ?string $creditDate;
    public ?string $estimatedCreditDate;
    public ?string $transactionReceiptUrl;
    public ?string $nossoNumero;
    public ?string $bankSlipUrl;
    public ?array  $discount;
    public ?array  $fine;
    public ?array  $interest;
    public ?array  $split;
    public ?bool   $postalService;
    public ?int    $daysAfterDueDateToRegistrationCancellation;
    public ?array  $chargeback;
    public ?array  $escrow;
    public ?array  $refunds;

    public function __construct(array $data)
    {

        $this->object                                     = data_get($data, 'object');
        $this->id                                         = data_get($data, 'id');
        $this->dateCreated                                = data_get($data, 'dateCreated');
        $this->customer                                   = data_get($data, 'customer');
        $this->subscription                               = data_get($data, 'subscription');
        $this->installment                                = data_get($data, 'installment');
        $this->checkoutSession                            = data_get($data, 'checkoutSession');
        $this->paymentLink                                = data_get($data, 'paymentLink');
        $this->value                                      = data_get($data, 'value');
        $this->netValue                                   = data_get($data, 'netValue');
        $this->originalValue                              = data_get($data, 'originalValue');
        $this->interestValue                              = data_get($data, 'interestValue');
        $this->description                                = data_get($data, 'description');
        $this->billingType                                = data_get($data, 'billingType');
        $this->creditCard                                 = data_get($data, 'creditCard');
        $this->canBePaidAfterDueDate                      = data_get($data, 'canBePaidAfterDueDate');
        $this->pixTransaction                             = data_get($data, 'pixTransaction');
        $this->pixQrCodeId                                = data_get($data, 'pixQrCodeId');
        $this->status                                     = data_get($data, 'status');
        $this->dueDate                                    = data_get($data, 'dueDate');
        $this->originalDueDate                            = data_get($data, 'originalDueDate');
        $this->paymentDate                                = data_get($data, 'paymentDate');
        $this->clientPaymentDate                          = data_get($data, 'clientPaymentDate');
        $this->installmentNumber                          = data_get($data, 'installmentNumber');
        $this->invoiceUrl                                 = data_get($data, 'invoiceUrl');
        $this->invoiceNumber                              = data_get($data, 'invoiceNumber');
        $this->externalReference                          = data_get($data, 'externalReference');
        $this->deleted                                    = data_get($data, 'deleted');
        $this->anticipated                                = data_get($data, 'anticipated');
        $this->anticipable                                = data_get($data, 'anticipable');
        $this->creditDate                                 = data_get($data, 'creditDate');
        $this->estimatedCreditDate                        = data_get($data, 'estimatedCreditDate');
        $this->transactionReceiptUrl                      = data_get($data, 'transactionReceiptUrl');
        $this->nossoNumero                                = data_get($data, 'nossoNumero');
        $this->bankSlipUrl                                = data_get($data, 'bankSlipUrl');
        $this->discount                                   = data_get($data, 'discount');
        $this->fine                                       = data_get($data, 'fine');
        $this->interest                                   = data_get($data, 'interest');
        $this->split                                      = data_get($data, 'split');
        $this->postalService                              = data_get($data, 'postalService');
        $this->daysAfterDueDateToRegistrationCancellation = data_get($data, 'daysAfterDueDateToRegistrationCancellation');
        $this->chargeback                                 = data_get($data, 'chargeback');
        $this->escrow                                     = data_get($data, 'escrow');
        $this->refunds                                    = data_get($data, 'refunds');
    }


}
