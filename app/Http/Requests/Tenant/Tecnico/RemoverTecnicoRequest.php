<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant\Tecnico;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;
use Validator;

class RemoverTecnicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [

            'id' => Helper::getIdByRequest($this->data, 'id'),

        ];
    }

    public function rules(): array
    {
        return [
            "id" => ["required", "integer", "exists:tb_tecnicos,idTecnico"],

        ];
    }

    public function messages(): array
    {
        return [
            "id.required" => "O campo id é obrigatório.",
            "id.integer"  => "O campo id deve ser um número inteiro.",
            "id.exists"   => "O id informado não existe.",

        ];
    }

    public function validated(): array
    {
        return Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
