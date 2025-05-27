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
            "materiais.*.idMaterial"    => ['required', 'integer', 'exists:tb_materiais,id'],
            "materiais.*.quantidade"    => ['required', 'integer', 'min:1'],
            "materiais.*.valorUnitario" => ['required'],
            "materiais.*.valorTotal"    => ['required'],
            "servicos.*.id"             => ['required', 'integer', 'exists:tb_servicos,id'],
            "servicos.*.quantidade"     => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required'                      => ':attribute é obrigatório',
            'dataAbertura.required'              => ':attribute é obrigatório',
            'dataEntrega.date'                   => ':attribute deve ser uma data válida',
            'status.required'                    => ':attribute é obrigatório',
            'valorTotal.required'                => ':attribute é obrigatório',
            'idCliente.required'                 => ':attribute é obrigatório',
            'idCliente.exists'                   => ':attribute informado não existe',
            'idTecnico.exists'                   => ':attribute informado não existe',
            'idAtendente.exists'                 => ':attribute informado não existe',
            'materiais.*.idMaterial.required'    => ':attribute é obrigatório',
            'materiais.*.idMaterial.exists'      => ':attribute informado não existe',
            'materiais.*.quantidade.required'    => ':attribute é obrigatório',
            'materiais.*.quantidade.min'         => ':attribute deve ser maior que 0',
            'materiais.*.valorUnitario.required' => ':attribute é obrigatório',
            'materiais.*.valorTotal.required'    => ':attribute é obrigatório',
            'servicos.*.id.required'             => ':attribute é obrigatório',
            'servicos.*.id.exists'               => ':attribute informado não existe',
            'servicos.*.quantidade.required'     => ':attribute é obrigatório',
            'servicos.*.quantidade.min'          => ':attribute deve ser maior que 0',


        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
