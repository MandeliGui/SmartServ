<?php

namespace App\Http\Requests\Tenant\Contrato;

use App\Enums\Periodicidade;
use App\Http\Requests\BaseValidationRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;

class CriarContratoRequest extends BaseValidationRequest
{
    public function __construct(private array $data, array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prepareForValidation();
    }

    public function prepareForValidation(): void
    {
        $this->data = [
            "idCliente"          => data_get($this->data, "idCliente"),
            "periodicidade"      => data_get($this->data, "periodicidade"),
            "materiais"          => $this->data['materiais'],
            "servicos"           => $this->data['servicos'],
            "dataInicioContrato" => data_get($this->data, "dataInicioContrato"),
        ];
    }

    public function rules(): array
    {
        return [
            'idCliente'                 => ['required', 'integer', 'exists:tb_cliente,idCliente'],
            'periodicidade'             => ['required', 'string', new Enum(Periodicidade::class)],
            "materiais.*.idMaterial"    => ['nullable', 'integer', 'exists:tb_materiais,id'],
            "materiais.*.quantidade"    => ['nullable', 'integer', 'min:1'],
            "materiais.*.valorUnitario" => ['nullable'],
            "materiais.*.valorTotal"    => ['nullable'],
            "servicos.*.idServico"      => ['nullable', 'integer', 'exists:tb_servicos,id'],
            "servicos.*.quantidade"     => ['nullable', 'integer', 'min:1'],
            "servicos.*.valorUnitario"  => ['nullable'],
            "servicos.*.valorTotal"     => ['nullable'],
            'dataInicioContrato'        => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'           => ':attribute é obrigatório.',
            'integer'            => ':attribute deve ser um número inteiro.',
            'exists'             => ':attribute informado não existe na tabela de clientes.',
            'string'             => ':attribute deve ser uma string.',
            'numeric'            => ':attribute deve ser um número.',
            'min'                => ':attribute deve ser no mínimo :min.',
            'in'                 => ':attribute deve ser um dos seguintes valores: ativo, inativo.',
            'periodicidade.enum' => ':attribute deve ser um dos seguintes valores: ' . implode(', ', array_map(function ($c) {
                    return $c->value ?? $c->name;
                }, Periodicidade::cases())),
            'date'               => ':attribute deve ser uma data válida.',
        ];
    }

    public function validated(): array
    {
        return \Validator::make($this->data, $this->rules(), $this->messages(), $this->attributes())->validated();
    }
}
