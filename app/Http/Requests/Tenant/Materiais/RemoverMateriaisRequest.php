<?php

namespace App\Http\Requests\Tenant\Materiais;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class RemoverMateriaisRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id' => Helper::getIdByRequest($this->data, 'id')
        ];
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:tb_materiais,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => ':attribute é obrigatório.',
            'id.exists'   => ':attribute não existe.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
