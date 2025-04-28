<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\GrupoClientes\CriarGrupoClientesRequest;
use App\Http\Requests\Tenant\GrupoClientes\EditarGrupoClienteRequest;
use App\Http\Requests\Tenant\GrupoClientes\RemoverGrupoClientesRequest;

class GrupoClientesRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array                $data,
        private readonly Persistence $persistence,
        private readonly array       $attributes = []
    ) {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => (new CriarGrupoClientesRequest($this->data, $this->attributes)),
            Persistence::UPDATE => (new EditarGrupoClienteRequest($this->data, $this->attributes)),
            Persistence::REMOVE => (new RemoverGrupoClientesRequest($this->data, $this->attributes)),
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
