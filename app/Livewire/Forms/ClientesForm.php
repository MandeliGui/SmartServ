<?php

declare(strict_types = 1);

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\ClienteRequest;
use App\Services\Tenant\ClienteService;
use Carbon\Carbon;
use Livewire\Form;

class ClientesForm extends Form
{
    public const MODAL_NAME_REMOVE                     = 'modal-cliente-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-cliente-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.clientes.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.clientes.remove';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-cliente';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-cliente-remove-multiple';
    public const EVENT_PERSISTED                       = 'cliente-persisted';

    public array $selectedsIds = [];

    public ?int    $id = null;

    public ?string $nomeRazaoSocial = null;

    public ?string $nomeFantasia = null;

    public ?string $telefone = null;

    public ?string $cpfCnpj = null;

    public ?string $dataNascimento = null;

    public ?string $email = null;

    public ?string $tipoPessoa = null;

    public ?int    $idGrupo = null;

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
            'telefone'             => 'Telefone',
            'email'                => 'Email',
            'cpfCnpj'              => 'CPF/CNPJ',
            'dataNascimento'       => 'Data de nascimento',
            'tipoPessoa'           => 'Tipo de pessoa',
            'idGrupo'              => 'Grupo',
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
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('d/m/Y', $this->dataNascimento)->format('Y-m-d') : null;

        $data = ClienteRequest::create($this->all(), $this->attributes())->validated();

        $cliente = (new ClienteService())->create($data);

        $this->reset();

        return $cliente;
    }

    public function update()
    {
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('d/m/Y', $this->dataNascimento)->format('Y-m-d') : null;

        $data = ClienteRequest::update($this->all(), $this->attributes())->validated();

        return (new ClienteService())->update($data);
    }

    public function remove(): void
    {
        $data = ClienteRequest::remove($this->id)->validated();

        (new ClienteService())->remove($data["id"]);

        $this->reset();
    }

    public function removeAll(): void
    {
        $data = ClienteRequest::removeMultiple($this->selectedsIds)->validated();

        (new ClienteService())->removeMultiple($data["ids"]);

        $this->reset();
    }

    public function setCliente(mixed $id): void
    {
        $cliente = (new ClienteService())->findOne($id);

        $this->id              = $cliente->idCliente;
        $this->nomeRazaoSocial = $cliente->pessoa->nomeRazaoSocial;
        $this->nomeFantasia    = $cliente->pessoa->nomeFantasia;
        $this->telefone        = $cliente->pessoa->telefone;
        $this->cpfCnpj         = $cliente->pessoa->cpfCnpj;
        $this->dataNascimento  = Carbon::parse($cliente->pessoa->dataNascimento)->format('d-m-Y');
        $this->email           = $cliente->pessoa->email;
        $this->idGrupo         = $cliente->idGrupo;

        $this->endereco = [
            'cep'         => $cliente->pessoa->endereco->cep,
            'rua'         => $cliente->pessoa->endereco->rua,
            'numero'      => $cliente->pessoa->endereco->numero,
            'bairro'      => $cliente->pessoa->endereco->bairro,
            'complemento' => $cliente->pessoa->endereco->complemento,
            'cidade'      => $cliente->pessoa->endereco->cidade,
            'uf'          => $cliente->pessoa->endereco->uf,
        ];
    }
}
