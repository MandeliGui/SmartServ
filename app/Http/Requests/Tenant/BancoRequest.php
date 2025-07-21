<?php

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\Banco\CriarBancoRequest;
use App\Http\Requests\Tenant\Banco\EditarBancoRequest;
use App\Http\Requests\Tenant\Banco\RemoverBancoRequest;

class BancoRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array       $data,
        private readonly Persistence $persistence,
        private readonly array       $attributes = []
    )
    {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => new CriarBancoRequest($this->data, $this->attributes),
            Persistence::UPDATE => new EditarBancoRequest($this->data, $this->attributes),
            Persistence::REMOVE => new RemoverBancoRequest($this->data, $this->attributes),
        };
    }

    public static function create(array $data, array $attributes = []): self
    {
        return new self($data, Persistence::CREATE, $attributes);
    }

    public static function update(array $data, array $attributes = []): self
    {
        return new self($data, Persistence::UPDATE, $attributes);
    }

    public static function remove(mixed $id, array $attributes = []): self
    {
        return new self(['id' => $id], Persistence::REMOVE, $attributes);
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
