<?php

declare(strict_types=1);

namespace App\Services\ExternalApi\Asaas\Endpoints\Request;

use App\Enums\Persistence;
use App\Http\Requests\BaseValidationRequest;
use App\Services\ExternalApi\Asaas\Endpoints\Request\Customer\CriarCustomerRequest;

class CustomerRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array       $data,
        private readonly Persistence $persistence,
        private readonly array       $attributes = []
    )
    {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => new CriarCustomerRequest($this->data, $this->attributes),
        };
    }

    public static function create(array $data, array $attributes = []): self
    {
        return new self($data, Persistence::CREATE, $attributes);
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function validated(): array
    {
        return $this->validationRequest->validated();
    }
}
