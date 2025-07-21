<?php

namespace App\Http\Requests\Tenant\EntradaSaida;

use App\Http\Requests\BaseValidationRequest;

class EditarEntradaSaidaRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        // TODO: Implement prepareForValidation() method.
    }

    public function rules(): array
    {
        // TODO: Implement rules() method.
    }

    public function messages(): array
    {
        // TODO: Implement messages() method.
    }

    public function validated(): array
    {
        // TODO: Implement validated() method.
    }
}
