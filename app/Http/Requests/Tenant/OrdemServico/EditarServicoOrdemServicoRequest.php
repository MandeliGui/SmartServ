<?php

namespace App\Http\Requests\Tenant\OrdemServico;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarServicoOrdemServicoRequest extends BaseValidationRequest
{

    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'            => Helper::getIdByRequest($this->data, 'idServicoSelecionado'),
            'quantidade'    => (int)$this->data['quantidade'],
            'descricao'     => $this->data['descricao'],
            'valorUnitario' => Helper::formatarValorMonetarioDB((float)$this->data['valorUnitario']),
            'valorTotal'    => Helper::formatarValorMonetarioDB((float)$this->data['valorTotal']),
        ];

    }

    public function rules(): array
    {
        return [
            'id'            => ['required', 'integer', 'exists:tb_ordem_servico_servico,id'],
            'quantidade'    => ['required', 'numeric'],
            'descricao'     => ['nullable', 'string'],
            'valorUnitario' => ['required', 'numeric'],
            'valorTotal'    => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer'  => 'O campo :attribute deve ser um número inteiro.',
            'numeric'  => 'O campo :attribute deve ser um número.',
            'string'   => 'O campo :attribute deve ser uma string.',
            'exists'   => 'O :attribute informado não existe.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
