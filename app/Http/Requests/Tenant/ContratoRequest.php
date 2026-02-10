<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Enums\Tenant\ContratoPersistence;
use App\Enums\Tenant\OrdemServicoPersistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\Contrato\CriarContratoRequest;
use App\Http\Requests\Tenant\Contrato\EditarContratoRequest;
use App\Http\Requests\Tenant\Contrato\EditarMaterialContratoRequest;
use App\Http\Requests\Tenant\Contrato\RemoverContratoRequest;
use App\Http\Requests\Tenant\Contrato\RemoverMaterialContratoRequest;

class ContratoRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array                           $data,
        private readonly Persistence|ContratoPersistence $persistence,
        private readonly array                           $attributes = []
    )
    {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE => new CriarContratoRequest($this->data, $this->attributes),
            Persistence::UPDATE => new EditarContratoRequest($this->data, $this->attributes),
            Persistence::REMOVE => new RemoverContratoRequest($this->data, $this->attributes),
            ContratoPersistence::EDITAR_MATERIAL => new EditarMaterialContratoRequest($this->data, $this->attributes),
            ContratoPersistence::REMOVER_MATERIAL => new RemoverMaterialContratoRequest($this->data, $this->attributes),

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

    public static function editarMaterial(array $data, array $attributes = []): self
    {
        return new self($data, ContratoPersistence::EDITAR_MATERIAL, $attributes);
    }

    public static function removeMaterial(int $id, array $attributes = []): self
    {
        return new self(['id' => $id], ContratoPersistence::REMOVER_MATERIAL, $attributes);
    }

    public static function editarServico(array $data, array $attributes = []): self
    {
        return new self($data, ContratoPersistence::EDITAR_SERVICO, $attributes);
    }

    public static function removeServico(int $id, array $attributes = []): self
    {
        return new self(['id' => $id], ContratoPersistence::REMOVER_SERVICO, $attributes);
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
