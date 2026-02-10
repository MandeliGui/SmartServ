<?php

namespace App\Http\Requests\Tenant\Contrato;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class RemoverMaterialContratoRequest extends BaseValidationRequest
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
            'id' => ['required', 'integer', 'exists:tb_contrato_materiais,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => ':attribute é obrigatório.',
            'id.integer'  => ':attribute deve ser um número inteiro.',
            'id.exists'   => ':attribute informado não existe na tabela de materiais da ordem de serviço.',
        ];
    }

    public function validated(): array
    {
//        dd(\Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->errors()->all());
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
