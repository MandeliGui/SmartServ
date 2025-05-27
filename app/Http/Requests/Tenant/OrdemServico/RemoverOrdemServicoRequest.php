<?php

namespace App\Http\Requests\Tenant\OrdemServico;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class RemoverOrdemServicoRequest extends BaseValidationRequest
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
            'id' => ['required', 'integer', 'exists:tb_ordem_servico,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => ':attribute é obrigatório',
            'id.integer'  => ':attribute deve ser um número inteiro',
            'id.exists'   => ':attribute não existe',
        ];
    }

    public function validated(): array
    {
        return [
            'id' => $this->data['id'],
        ];
    }
}
