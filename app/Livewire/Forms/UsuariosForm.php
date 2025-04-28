<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Http\Requests\Tenant\UsuariosRequest;
use App\Services\Tenant\UsuarioService;
use Carbon\Carbon;
use Livewire\Form;

class UsuariosForm extends Form
{
    public const MODAL_NAME_REMOVE                     = 'modal-usuario-remove';
    public const MODAL_NAME_REMOVE_MULTIPLE            = 'modal-usuario-remove-multiple';
    public const PATH_COMPONENT_FORM_CREATE_AND_UPDATE = 'forms.usuarios.create-update';
    public const PATH_COMPONENT_FORM_REMOVE            = 'forms.usuarios.remove';
    public const EVENT_NAME_SHOW_MODAL_REMOVE          = 'show-modal-remove-usuario';
    public const EVENT_NAME_SHOW_MODAL_REMOVE_MULTIPLE = 'show-modal-usuario-remove-multiple';
    public const EVENT_PERSISTED                       = 'usuario-persisted';

    public array $selectedsIds = [];

    public ?int $id = null;

    public ?string $nomeRazaoSocial = null;

    public ?string $nomeFantasia = null;

    public ?string $telefone = null;

    public ?string $cpfCnpj = null;

    public ?string $dataNascimento = null;

    public ?string $email = null;

    public ?string $tipoPessoa = null;

    public ?int $idGrupo = null;

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

        $data = UsuariosRequest::create($this->all(), $this->attributes())->validated();

        $usuario = (new UsuarioService())->create($data);

        $this->reset();

        return $usuario;
    }

    public function update()
    {
        $this->dataNascimento = $this->dataNascimento !== null && $this->dataNascimento !== '' && $this->dataNascimento !== '0' ? Carbon::createFromFormat('d/m/Y', $this->dataNascimento)->format('Y-m-d') : null;

        $data = UsuariosRequest::update($this->all(), $this->attributes())->validated();

        return (new UsuarioService())->update($data);
    }

    public function remove(): void
    {
        $data = UsuariosRequest::remove($this->id)->validated();

        (new UsuarioService())->remove($data["id"]);

        $this->reset();
    }

    public function removeAll(): void
    {
        $data = UsuariosRequest::removeMultiple($this->selectedsIds)->validated();

        (new UsuarioService())->removeMultiple($data["ids"]);

        $this->reset();
    }

    public function setCliente(mixed $id): void
    {
        $usuario = (new UsuarioService())->findOne($id);

        $this->id              = $usuario->idCliente;
        $this->nomeRazaoSocial = $usuario->pessoa->nomeRazaoSocial;
        $this->nomeFantasia    = $usuario->pessoa->nomeFantasia;
        $this->telefone        = $usuario->pessoa->telefone;
        $this->cpfCnpj         = $usuario->pessoa->cpfCnpj;
        $this->dataNascimento  = Carbon::parse($usuario->pessoa->dataNascimento)->format('d-m-Y');
        $this->email           = $usuario->pessoa->email;
        $this->idGrupo         = $usuario->idGrupo;

        $this->endereco = [
            'cep'         => $usuario->pessoa->endereco->cep,
            'rua'         => $usuario->pessoa->endereco->rua,
            'numero'      => $usuario->pessoa->endereco->numero,
            'bairro'      => $usuario->pessoa->endereco->bairro,
            'complemento' => $usuario->pessoa->endereco->complemento,
            'cidade'      => $usuario->pessoa->endereco->cidade,
            'uf'          => $usuario->pessoa->endereco->uf,
        ];
    }
}
