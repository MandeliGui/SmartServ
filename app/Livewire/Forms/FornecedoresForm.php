<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\FornecedorRequest;
use App\Services\Tenant\FornecedoresService;
use Carbon\Carbon;
use Flux\Flux;
use Livewire\Form;

class FornecedoresForm extends Form
{
    public const MODAL_NAME_REMOVE                     = 'modal-fornecedor-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-fornecedor-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.fornecedores.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.fornecedores.remove';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-fornecedor';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-fornecedor-remove-multiple';
    public const EVENT_PERSISTED                       = 'fornecedor-persisted';

    public array $selectedsIds = [];

    public ?int $id = null;

    public ?string $nomeRazaoSocial = null;

    public ?string $nomeFantasia = null;
    public ?string $atendente    = null;

    public ?string $telefone = null;

    public ?string $cnpj = null;


    public ?string $email = null;


    public array $endereco = [
        'cep'         => null,
        'rua'         => null,
        'numero'      => null,
        'bairro'      => null,
        'complemento' => null,
        'cidade'      => null,
        'uf'          => null,
    ];


    private function attributes(): array
    {
        return [
            'nomeRazaoSocial'      => 'Nome/Razão Social',
            'nomeFantasia'         => 'Nome Fantasia',
            'atendente'            => 'Atendente',
            'telefone'             => 'Telefone',
            'email'                => 'Email',
            'Cnpj'                 => 'CNPJ',
            'endereco.cep'         => 'CEP',
            'endereco.rua'         => 'Rua',
            'endereco.numero'      => 'Número',
            'endereco.bairro'      => 'Bairro',
            'endereco.complemento' => 'Complemento',
            'endereco.cidade'      => 'Cidade',
            'endereco.uf'          => 'UF',
        ];
    }

    public function create()
    {


        $data = FornecedorRequest::create($this->all(), $this->attributes())->validated();


        $fornecedor = (new FornecedoresService())->create($data);

    }

    public function update()
    {
        $data = FornecedorRequest::update($this->all(), $this->attributes())->validated();

        (new FornecedoresService())->update($data);
    }

    public function remove(): void
    {
        $data = FornecedorRequest::remove($this->id)->validated();

        (new FornecedoresService())->remove($data["id"]);

        $this->reset();
    }

    public function removeAll(): void
    {
        $data = FornecedorRequest::removeMultiple($this->selectedsIds)->validated();

        (new FornecedoresService())->removeMultiple($data["ids"]);

        $this->reset();
    }

    public function setFornecedor(mixed $id): void
    {
        $fornecedor = (new FornecedoresService())->findOne($id);

        $this->id              = $fornecedor->id;
        $this->nomeRazaoSocial = $fornecedor->razao_social;
        $this->nomeFantasia    = $fornecedor->nome_fantasia;
        $this->atendente       = $fornecedor->atendente;
        $this->telefone        = $fornecedor->telefone;
        $this->cnpj            = $fornecedor->cnpj;
        $this->email           = $fornecedor->email;

        $this->endereco = [
            'cep'         => $fornecedor->endereco->cep,
            'rua'         => $fornecedor->endereco->rua,
            'numero'      => $fornecedor->endereco->numero,
            'bairro'      => $fornecedor->endereco->bairro,
            'complemento' => $fornecedor->endereco->complemento,
            'cidade'      => $fornecedor->endereco->cidade,
            'uf'          => $fornecedor->endereco->uf,
        ];
    }
}
