<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\Cliente\CriarClienteRequest;
use App\Http\Requests\Tenant\Cliente\EditarClienteRequest;
use App\Http\Requests\Tenant\Cliente\RemoverClienteRequest;
use App\Http\Requests\Tenant\OrdemServico\CriarOrdemServicoRequest;
use App\Http\Requests\Tenant\OrdemServico\EditarOrdemServicoRequest;
use App\Http\Requests\Tenant\OrdemServico\RemoverOrdemServicoRequest;

class OrdemServicoRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array                $data,
        private readonly Persistence $persistence,
        private readonly array       $attributes = []
    ) {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => new CriarOrdemServicoRequest($this->data, $this->attributes),
            Persistence::UPDATE => new EditarOrdemServicoRequest($this->data, $this->attributes),
            Persistence::REMOVE => new RemoverOrdemServicoRequest($this->data, $this->attributes),
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

    public static function removeMultiple(array $ids): self
    {
        return new self(["ids" => $ids], Persistence::REMOVE_MULTIPLE);
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
