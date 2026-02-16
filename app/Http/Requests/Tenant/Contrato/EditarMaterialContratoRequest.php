<?php

namespace App\Http\Requests\Tenant\Contrato;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarMaterialContratoRequest extends BaseValidationRequest
{

    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'            => Helper::getIdByRequest($this->data, 'idMaterialSelecionado'),
            'quantidade'    => (int)$this->data['quantidade'],
            'valorUnitario' => Helper::formatarValorMonetarioDB((float)$this->data['valorUnitario']),
            'valorTotal'    => Helper::formatarValorMonetarioDB((float)$this->data['valorTotal']),
            'descricao' => data_get($this->data, 'descricao'),
        ];
    }

    public function rules(): array
    {
        return [
            'id'            => ['required', 'integer', 'exists:tb_contrato_materiais,id'],
            'quantidade'    => ['required', 'numeric'],
            'valorUnitario' => ['required', 'numeric'],
            'valorTotal'    => ['required', 'numeric'],
            'descricao' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'            => ':attribute é obrigatório.',
            'id.integer'             => ':attribute deve ser um número inteiro.',
            'id.exists'              => ':attribute informado não existe na tabela de materiais da ordem de serviço.',
            'quantidade.required'    => ':attribute é obrigatório.',
            'quantidade.numeric'     => ':attribute deve ser um número.',
            'valorUnitario.required' => ':attribute é obrigatório.',
            'valorUnitario.numeric'  => ':attribute deve ser um número.',
            'valorTotal.required'    => ':attribute é obrigatório.',
            'valorTotal.numeric'     => ':attribute deve ser um número.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
