<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\Persistence;
use App\Enums\Tenant\OrdemServicoPersistence;
use App\Http\Requests\BaseValidationRequest;
use App\Http\Requests\Tenant\Cliente\CriarClienteRequest;
use App\Http\Requests\Tenant\Cliente\EditarClienteRequest;
use App\Http\Requests\Tenant\Cliente\RemoverClienteRequest;
use App\Http\Requests\Tenant\OrdemServico\CriarOrdemServicoRequest;
use App\Http\Requests\Tenant\OrdemServico\EditarOrdemServicoRequest;
use App\Http\Requests\Tenant\OrdemServico\RemoverMaterialOrdemServicoRequest;
use App\Http\Requests\Tenant\OrdemServico\RemoverOrdemServicoRequest;
use App\Http\Requests\Tenant\OrdemServico\RemoverServicoOrdemSericoRequest;
use App\Http\Requests\Tenant\Servicos\RemoverServicoRequest;

class OrdemServicoRequest
{
    private readonly BaseValidationRequest $validationRequest;

    public function __construct(
        private readonly array                               $data,
        private readonly Persistence|OrdemServicoPersistence $persistence,
        private readonly array                               $attributes = []
    )
    {
        $this->validationRequest = match ($this->persistence) {
            Persistence::CREATE                       => new CriarOrdemServicoRequest($this->data, $this->attributes),
            Persistence::UPDATE                       => new EditarOrdemServicoRequest($this->data, $this->attributes),
            Persistence::REMOVE                       => new RemoverOrdemServicoRequest($this->data, $this->attributes),
            OrdemServicoPersistence::REMOVER_MATERIAL => new RemoverMaterialOrdemServicoRequest($this->data, $this->attributes),
            OrdemServicoPersistence::REMOVER_SERVICO  => new RemoverServicoOrdemSericoRequest($this->data, $this->attributes),
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

    public static function removeMaterial(int $id, array $attributes = []): self
    {
        return new self(['id' => $id], OrdemServicoPersistence::REMOVER_MATERIAL, $attributes);
    }

    public static function removeServico(int $id, array $attributes = []): self
    {
        return new self(['id' => $id], OrdemServicoPersistence::REMOVER_SERVICO, $attributes);
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
