<?php

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\FormaPagamento\CriarFormaPagamentoRequest;
use App\Http\Requests\Tenant\FormaPagamento\EditarFormaPagamentoRequest;
use App\Http\Requests\Tenant\FormaPagamento\RemoverFormaPagamentoRequest;

class FormaPagamentoRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array       $data,
        private readonly Persistence $persistence,
        private readonly array       $attributes = []
    )
    {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => new CriarFormaPagamentoRequest($this->data, $this->attributes),
            Persistence::UPDATE => new EditarFormaPagamentoRequest($this->data, $this->attributes),
            Persistence::REMOVE => new RemoverFormaPagamentoRequest($this->data, $this->attributes),
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
