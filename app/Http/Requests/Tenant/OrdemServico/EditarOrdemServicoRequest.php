<?php

namespace App\Http\Requests\Tenant\OrdemServico;

use App\Helpers\Helper;
use App\Http\Requests\BaseValidationRequest;

class EditarOrdemServicoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            'id'           => Helper::getIdByRequest($this->data, 'id'),
            'tipo'         => $this->data['tipo'],
            'dataAbertura' => $this->data['dataAbertura'],
            'dataEntrega'  => $this->data['dataEntrega'],
            'status'       => $this->data['status'],
            'valorTotal'   => $this->data['valorTotal'],
            'idCliente'    => $this->data['idCliente'],
            'idTecnico'    => $this->data['idTecnico'],
            'idAtendente'  => $this->data['idAtendente'],
            "materiais"    => [
                "id"         => $this->data['materiais']['id'],
                "quantidade" => $this->data['materiais']['quantidade'],
            ],
            "servicos"     => [
                "id"         => $this->data['servicos']['id'],
                "quantidade" => $this->data['servicos']['quantidade'],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            'id'                   => ['required', 'integer', 'exists:tb_ordem_servico,id'],
            'tipo'                 => ['required', 'string'],
            'dataAbertura'         => ['required', 'date'],
            'dataEntrega'          => ['nullable', 'date'],
            'status'               => ['required', 'string'],
            'valorTotal'           => ['required', 'numeric'],
            'idCliente'            => ['required', 'integer', 'exists:tb_clientes,idCliente'],
            'idTecnico'            => ['nullable', 'integer', 'exists:tb_tecnicos,idTecnico'],
            'idAtendente'          => ['nullable', 'integer', 'exists:tb_atendentes,idAtendente'],
            "materiais.id"         => ['required', 'integer', 'exists:tb_materiais,id'],
            "materiais.quantidade" => ['required', 'integer', 'min:1'],
            "servicos.id"          => ['required', 'integer', 'exists:tb_servicos,id'],
            "servicos.quantidade"  => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'                   => ':attribute é obrigatório',
            'id.integer'                    => ':attribute deve ser um número inteiro',
            'id.exists'                     => ':attribute não existe',
            'tipo.required'                 => ':attribute é obrigatório',
            'dataAbertura.required'         => ':attribute é obrigatório',
            'dataEntrega.date'              => ':attribute deve ser uma data válida',
            'status.required'               => ':attribute é obrigatório',
            'valorTotal.required'           => ':attribute é obrigatório',
            'idCliente.required'            => ':attribute é obrigatório',
            'idCliente.exists'              => ':attribute informado não existe',
            'idTecnico.exists'              => ':attribute informado não existe',
            'idAtendente.exists'            => ':attribute informado não existe',
            'materiais.id.required'         => ':attribute é obrigatório',
            'materiais.id.exists'           => ':attribute informado não existe',
            'materiais.quantidade.required' => ':attribute é obrigatório',
            'materiais.quantidade.integer'  => ':attribute deve ser um número inteiro',
            'materiais.quantidade.min'      => ':attribute deve ser maior que 0',
            'servicos.id.required'          => ':attribute é obrigatório',
            'servicos.id.exists'            => ':attribute informado não existe',
            'servicos.quantidade.required'  => ':attribute é obrigatório',
            'servicos.quantidade.integer'   => ':attribute deve ser um número inteiro',
            'servicos.quantidade.min'       => ':attribute deve ser maior que 0',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
