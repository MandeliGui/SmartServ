<?php

namespace App\Http\Requests\Tenant\OrdemServico;

use App\Http\Requests\BaseValidationRequest;

class CriarOrdemServicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {

        $this->data = [
            'codigo'       => $this->data['codigo'],
            'tipo'         => $this->data['tipo'],
            'dataAbertura' => $this->data['dataAbertura'],
            'dataEntrega'  => $this->data['dataEntrega'],
            'status'       => $this->data['status'],
            'valorTotal'   => $this->data['valorTotal'],
            'idCliente'    => $this->data['idCliente'],
            'idTecnico'    => $this->data['idTecnico'],
            'idAtendente'  => $this->data['idAtendente'],
            "materiais"    => $this->data['materiais'],
            "servicos"     => $this->data['servicos'],
        ];

    }

    public function rules(): array
    {
        return [
            'codigo'                    => ['required', 'string'],
            'tipo'                      => ['required', 'string'],
            'dataAbertura'              => ['required', 'date'],
            'dataEntrega'               => ['nullable', 'date'],
            'status'                    => ['required', 'string'],
            'valorTotal'                => ['required', 'numeric'],
            'idCliente'                 => ['required', 'integer', 'exists:tb_cliente,idCliente'],
            'idTecnico'                 => ['nullable', 'integer', 'exists:tb_tecnicos,idTecnico'],
            'idAtendente'               => ['nullable', 'integer', 'exists:tb_atendentes,idAtendente'],
            "materiais.*.idMaterial"    => ['nullable', 'integer', 'exists:tb_materiais,id'],
            "materiais.*.quantidade"    => ['nullable', 'integer', 'min:1'],
            "materiais.*.descricao"     => ['nullable', 'string'],
            "materiais.*.valorUnitario" => ['nullable'],
            "materiais.*.valorTotal"    => ['nullable'],
            "servicos.*.idServico"      => ['nullable', 'integer', 'exists:tb_servicos,id'],
            "servicos.*.quantidade"     => ['nullable', 'integer', 'min:1'],
            "servicos.*.descricao"      => ['nullable', 'string'],
            "servicos.*.valorUnitario"  => ['nullable'],
            "servicos.*.valorTotal"     => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            "required" => "O campo :attribute é obrigatório.",
            "string"   => "O campo :attribute deve ser uma string.",
            "date"     => "O campo :attribute deve ser uma data válida.",
            "numeric"  => "O campo :attribute deve ser um número.",
            "integer"  => "O campo :attribute deve ser um número inteiro.",
            "exists"   => "O :attribute selecionado é inválido.",
            "min"      => "O campo :attribute deve ter no mínimo :min.",


        ];
    }

    public function validated(): array
    {

        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
