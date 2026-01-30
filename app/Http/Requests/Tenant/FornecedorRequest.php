<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\Fornecedores\CriarFornecedorRequest;
use App\Http\Requests\Tenant\Fornecedores\EditarFornecedorRequest;
use App\Http\Requests\Tenant\Fornecedores\RemoverFornecedorRequest;

class FornecedorRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array       $data,
        private readonly Persistence $persistence,
        private readonly array       $attributes = []
    )
    {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => new CriarFornecedorRequest($this->data, $this->attributes),
            Persistence::UPDATE => new EditarFornecedorRequest($this->data, $this->attributes),
            Persistence::REMOVE => new RemoverFornecedorRequest($this->data, $this->attributes),
        };
    }

    public static function create(array $data, array $attributes = []): self
    {
        return new self($data, Persistence::CREATE, $attributes);
    }

    public static function findOneById(mixed $id): self
    {
        return new self(["id" => $id], Persistence::FIND_ONE_BY_ID);
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
