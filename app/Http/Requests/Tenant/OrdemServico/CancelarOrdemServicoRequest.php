<?php

namespace App\Http\Requests\Tenant\OrdemServico;

use App\Enums\StatusOrdemServico;
use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class CancelarOrdemServicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'     => Helper::getIdByRequest($this->data, 'id'),
            'status' => StatusOrdemServico::CANCELADO->value,
        ];
    }

    public function rules(): array
    {
        return [
            'id'     => ['required', 'integer', 'exists:tb_ordem_servico,id'],
            'status' => ['required', 'string', 'in:' . StatusOrdemServico::CANCELADO->value],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'     => ':attribute é obrigatório.',
            'id.integer'      => ':attribute deve ser um número inteiro.',
            'id.exists'       => ':attribute informado não existe na tabela de ordens de serviço.',
            'status.required' => ':attribute é obrigatório.',
            'status.string'   => ':attribute deve ser uma string.',
            'status.in'       => ':attribute deve ser um dos seguintes valores: ' . StatusOrdemServico::CANCELADO->value,
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
